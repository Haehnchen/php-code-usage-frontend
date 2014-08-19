<?php

namespace espend\Inspector\ImportBundle\Command;


use Doctrine\ORM\EntityManager;
use espend\Inspector\CoreBundle\Entity\InspectorProject;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class LockImportCommand extends ContainerAwareCommand {

    /**
     * @var EntityManager
     */
    private $em;

    protected function configure() {
        $this->setName('inspector:composer:lock_import');
    }

    function startswith1($haystack, $needle) {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $dir = dirname($this->getContainer()->get('kernel')->getRootDir()) . '/inspector';

        $finder = new Finder();
        $finder->files()->in($dir);
        $finder->name('*composer.lock');

        /** @var SplFileInfo[] $iterator */
        $iterator = $finder->getIterator();

        foreach ($iterator as $file) {
            $output->writeln(sprintf('%s', $file->getRelativePathname()));
            $this->fileVisitor($file);
        }

    }

    /**
     * @param $file
     */
    private function fileVisitor(SplFileInfo $file) {

        $content = json_decode($file->getContents(), true);
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        foreach ($content['packages'] as $package) {

            $packageEntity = $em->getRepository('espendInspectorCoreBundle:InspectorProject')->findOneBy(array(
                'name' => $package['name'],
            ));

            if ($packageEntity != null) {
                if (isset($package['source']['reference'])) {
                    $packageEntity->setSourceReference($package['source']['reference']);
                }

                if (isset($package['source']['url']) && isset($package['source']['reference'])) {

                    $url = null;
                    if (preg_match('#/github.com/(.*)$#i', $package['source']['url'], $result)) {
                        $url = 'https://github.com/' . trim($result[1], '.git') . '/blob/' . $package['source']['reference'] . '/%file%#L%line%';
                    }

                    $packageEntity->setSourceUrl($url);
                }

                if (isset($package['source']['reference'])) {
                    $packageEntity->setVersion($package['version']);
                }
            }

            // package replace feature
            if (isset($package['replace']) && isset($package['source']['reference'])) {
                $y = array_keys($package['replace']);
                if(count($y) > 0) {

                    $qb = $em->getRepository('espendInspectorCoreBundle:InspectorProject')->createQueryBuilder('project')->update();

                    $qb->set('project.source_reference', $qb->expr()->literal($package['source']['reference']));

                    if(isset($package['version'])) {
                        $qb->set('project.version', $qb->expr()->literal($package['version']));
                    }

                    if(isset($package['source']['url'])) {
                        if (preg_match('#/github.com/(.*)$#i', $package['source']['url'], $result)) {
                            $url = 'https://github.com/' . trim($result[1], '.git') . '/blob/' . $package['source']['reference'] . '/%file%#L%line%';
                            $qb->set('project.source_url', $qb->expr()->literal($url));
                        }
                    }

                    $qb->andWhere($qb->expr()->in('project.name', $y));
                    $qb->getQuery()->execute();
                }

            }

        }

        $em->flush();
    }

}