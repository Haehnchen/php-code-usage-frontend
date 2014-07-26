<?php

namespace espend\Inspector\ImportBundle\Command;


use Doctrine\ORM\EntityManager;
use espend\Inspector\CoreBundle\Entity\InspectorProject;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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


        $file = dirname($this->getContainer()->get('kernel')->getRootDir()) . '/inspector/composer.lock';
        if(!is_file($file)) {
            $output->writeln('not found: ' . $file);
            return;
        }

        $content = json_decode(file_get_contents($file), true);
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        foreach($content['packages'] as $package) {


            $packageEntity = $em->getRepository('espendInspectorCoreBundle:InspectorProject')->findOneBy(array(
               'name' => $package['name'],
            ));

            if($packageEntity != null) {
                if(isset($package['source']['reference'])) {
                    $packageEntity->setSourceReference($package['source']['reference']);
                }

                if (isset($package['source']['url']) && isset($package['source']['reference'])) {

                    $url = null;
                    if ( preg_match('#/github.com/(.*)$#i', $package['source']['url'], $result)) {
                        $url = 'https://github.com/' . trim($result[1], '.git') . '/blob/'. $package['source']['reference'] .'/%file%#L%line%';
                    }

                    $packageEntity->setSourceUrl($url);
                }

                if (isset($package['source']['reference'])) {
                    $packageEntity->setVersion($package['version']);
                }
            }

            $em->flush();
        }


    }

}