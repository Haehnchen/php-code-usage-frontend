<?php

namespace espend\Inspector\ImportBundle\Command;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;
use espend\Inspector\CoreBundle\Entity\InspectorClass;
use espend\Inspector\CoreBundle\Entity\InspectorFile;
use espend\Inspector\CoreBundle\Entity\InspectorInstance;
use espend\Inspector\CoreBundle\Entity\InspectorMethod;
use espend\Inspector\CoreBundle\Entity\InspectorMethodChild;
use espend\Inspector\CoreBundle\Entity\InspectorProject;
use espend\Inspector\CoreBundle\Entity\InspectorSuper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class JsonImportCommand extends ContainerAwareCommand {

    private $classCache = array();
    private $projectCache = array();

    /**
     * @var EntityManager
     */
    private $em;

    private $garbage = array();

    protected function configure() {
        $this->setName('inspector:import');
    }

    function startswith1($haystack, $needle) {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $this->em = $this->getContainer()->get('doctrine')->getManager();

        $finder = new Finder();

        $this->getContainer()->get('doctrine')->getConnection()->getConfiguration()->setSQLLogger(null);

        $dbal = $this->getContainer()->get('espend_inspector_core.dbal_query');

        foreach ($this->getContainer()->get('doctrine')->getManager()->getRepository('espendInspectorCoreBundle:InspectorClass')->findAll() as $class) {
            $this->classCache[$class->getId()] = $class;
        };

        foreach ($this->getContainer()->get('doctrine')->getManager()->getRepository('espendInspectorCoreBundle:InspectorProject')->findAll() as $project) {
            $this->projectCache[$project->getId()] = $project;
        };

        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var SplFileInfo[] $files */
        $files = $finder->in(dirname($this->getContainer()->get('kernel')->getRootDir()) . '/inspector');

        $max = count($files);

        $progress = new ProgressBar($output, $max);
        $progress->start();
        $progress->setMessage('Task is in progress...');

        $i = 0;
        foreach ($files as $file) {

            $output->writeln(sprintf('%s/%s', $i++, $max));

            

            $contents = json_decode($file->getContents(), true);

            if(!isset($contents['file']) || !isset($contents['name'])) {
                $output->writeln('skipping ' . $file->getFilename());
                continue;
            }

            $project = $this->getProject($contents['name']);
            $fileEntity = $this->getFile($contents['file'], $project);

            foreach ($contents['items'] as $json) {

                if (!isset($json['type'])) {
                    continue;
                }

                if ($json['type'] == 'instance') {
                    $this->visitInstance($json, $fileEntity);
                }

                if ($json['type'] == 'extends' || $json['type'] == 'implements') {
                    $this->visitSuper($json);
                }

                if ($json['type'] == 'method') {
                    $this->visitMethod($json, $fileEntity);
                }

                $em->flush();

            }

            $progress->advance();

            $em->clear($fileEntity);

            if(($i % 5) == 0) {

                /** @var UnitOfWork $unitOfWork */
                $unitOfWork = $em->getUnitOfWork();

                $before = $unitOfWork->size();

                $em->clear('espend\Inspector\CoreBundle\Entity\InspectorFile');
                $em->clear('espend\Inspector\CoreBundle\Entity\InspectorSuper');
                $em->clear('espend\Inspector\CoreBundle\Entity\InspectorMethod');
                $em->clear('espend\Inspector\CoreBundle\Entity\InspectorMethodChild');
                $em->clear('espend\Inspector\CoreBundle\Entity\InspectorInstance');

                $output->writeln(sprintf('cleaned %d left %d', $before, $unitOfWork->size()));
            }

        }

        $progress->finish();

    }

    private function getClass($className) {

        if (isset($this->classCache[$className])) {
            return $this->classCache[$className];
        }

        $class = $this->getContainer()->get('doctrine')->getRepository('espendInspectorCoreBundle:InspectorClass')->findOneBy(array(
            'class' => $className,
        ));

        if (!$class) {
            $class = new InspectorClass();
            $class->setClass($className);
            $class->setLastFoundAt(new \DateTime());
        }

        $class->setLastFoundAt(new \DateTime());

        $this->getContainer()->get('doctrine')->getManager()->persist($class);
        $this->getContainer()->get('doctrine')->getManager()->flush($class);

        return $this->classCache[$className] = $class;

    }

    private function getProject($projectName) {

        if (isset($this->projectCache[$projectName])) {
            return $this->projectCache[$projectName];
        }

        $class = $this->getContainer()->get('doctrine')->getRepository('espendInspectorCoreBundle:InspectorProject')->findOneBy(array(
            'name' => $projectName,
        ));

        if (!$class) {
            $class = new InspectorProject();
            $class->setName($projectName);
            //$class->setLastFoundAt(new \DateTime());
        }

        $class->setLastFoundAt(new \DateTime());

        $this->getContainer()->get('doctrine')->getManager()->persist($class);
        $this->getContainer()->get('doctrine')->getManager()->flush($class);

        return $this->projectCache[$projectName] = $class;

    }

    private function getFile($filename, InspectorProject $project) {

        $file = $this->getContainer()->get('doctrine')->getRepository('espendInspectorCoreBundle:InspectorFile')->findOneBy(array(
            'name' => $filename,
            'project' => $project->getId(),
        ));

        if (!$file) {
            $file = new InspectorFile();
            $file->setName($filename);
            $file->setProject($project);
        }

        $file->setLastFoundAt(new \DateTime());

        $this->getContainer()->get('doctrine')->getManager()->persist($file);
        $this->getContainer()->get('doctrine')->getManager()->flush($file);

        return $file;
    }

    private function visitMethod(array $json, InspectorFile $file) {

        $class = $this->getClass($json['class']);

        $key = $file->getProject()->getId() . '-' . $json['key'];
        $method = $this->getContainer()->get('doctrine')->getRepository('espendInspectorCoreBundle:InspectorMethod')->findOneBy(array(
            'key' => $key,
        ));

        if (!$method) {
            $method = new InspectorMethod();
            $method->setClass($class);
            $method->setMethod($json['name']);
            $method->setKey($key);
            $method->setFile($file);

            $method->setContext($json['context']['context']);
            $method->setLine($json['context']['line']);
            $method->setLastFoundAt(new \DateTime());

            $this->getContainer()->get('doctrine')->getManager()->persist($method);
            $this->getContainer()->get('doctrine')->getManager()->flush($method);
        }

        $method->setFile($file);
        $method->setContext($json['context']['context']);
        $method->setLine($json['context']['line']);
        $method->setLastFoundAt(new \DateTime());

        $this->getContainer()->get('doctrine')->getManager()->persist($method);

        foreach($json['methods'] as $superClass) {

            $class = $this->getClass($superClass['class']);

            $super = $this->getContainer()->get('doctrine')->getRepository('espendInspectorCoreBundle:InspectorMethodChild')->findOneBy(array(
                'class' => $class->getId(),
                'method' => $method->getId(),
            ));

            if($super == null) {
                $super = new InspectorMethodChild();
                $super->setClass($class);
                $super->setMethod($method);
            }

            $super->setLastFoundAt(new \DateTime());

            $this->em->persist($super);

        }

    }

    /**
     * @param $json
     * @param $em
     * @param $fileEntity
     * @return InspectorClass
     */
    protected function visitInstance($json, InspectorFile $fileEntity) {
        $class = $this->getClass($json['class']);

        $key = $fileEntity->getId() . '-' . $json['key'];
        $instance = $this->em->getRepository('espendInspectorCoreBundle:InspectorInstance')->findOneBy(array(
            'key' => $key,
        ));

        if ($instance == null) {
            $instance = new InspectorInstance();
            $instance->setClass($class);
            $instance->setFile($fileEntity);
            $instance->setKey($key);
        }

        $instance->setContext($json['context']['context']);
        $instance->setLine($json['context']['line']);

        $instance->setLastFoundAt(new \DateTime());

        $this->em->persist($instance);

    }

    /**
     * @param $json
     */
    protected function visitSuper($json) {
        $class = $this->getClass($json['class']);
        $child = $this->getClass($json['child']);

        $instanceSuper = $this->em->getRepository('espendInspectorCoreBundle:InspectorSuper')->findOneBy(array(
            'class' => $class->getId(),
            'child' => $child->getId(),
        ));

        if ($instanceSuper == null) {
            $instanceSuper = new InspectorSuper();
            $instanceSuper->setSuperType($json['type']);
            $instanceSuper->setClass($class);
            $instanceSuper->setChild($child);
        }

        $instanceSuper->setLastFoundAt(new \DateTime());
        $this->em->persist($instanceSuper);

    }

}