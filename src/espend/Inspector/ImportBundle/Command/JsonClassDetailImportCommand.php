<?php

namespace espend\Inspector\ImportBundle\Command;

use espend\Inspector\CoreBundle\Entity\InspectorFile;
use espend\Inspector\CoreBundle\Entity\InspectorProject;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class JsonClassDetailImportCommand extends ContainerAwareCommand {

    private $projectCache = array();

    protected function configure() {
        $this->setName('inspector:import:classes');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $this->getContainer()->get('doctrine')->getConnection()->getConfiguration()->setSQLLogger(null);

        foreach ($this->getContainer()->get('doctrine')->getManager()->getRepository('espendInspectorCoreBundle:InspectorProject')->findAll() as $project) {
            $this->projectCache[$project->getId()] = $project;
        };

        $em = $this->getContainer()->get('doctrine')->getManager();

        $finder = new Finder();

        /** @var SplFileInfo[] $files */
        $files = $finder->in(dirname($this->getContainer()->get('kernel')->getRootDir()) . '/inspector');

        $max = count($files);

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

                if ($json['type'] == 'class') {
                    $this->updateClass($json['class'], $fileEntity, $json);
                }

            }

            $em->clear($fileEntity);
            $em->clear('espend\Inspector\CoreBundle\Entity\InspectorFile');

        }

    }

    private function updateClass($className, InspectorFile $file, $json = array()) {

        $item = array(
            'last_found_at' => date_create()->format('Y-m-d'),
            'project_id' => $file->getProject()->getId(),
            'file_id' => $file->getId(),
            'class' => $className,
            'doc_comment' => null,
        );

        if (isset($json['doc_comment'])) {
            $result = trim(preg_replace('%(\r?\n(?! \* ?@))?^(/\*\*\r?\n \* | \*/| \* ?)%m', ' ', $json['doc_comment']));
            $item['doc_comment'] = strlen($result) > 0 ? $result : null;
        }

        $this->getContainer()->get('espend_inspector_core.dbal_query')->executePdoUpsert('inspector_class', $item);

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

}