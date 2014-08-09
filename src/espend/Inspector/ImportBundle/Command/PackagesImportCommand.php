<?php

namespace espend\Inspector\ImportBundle\Command;


use Doctrine\ORM\EntityManager;
use espend\Inspector\CoreBundle\Entity\InspectorProject;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PackagesImportCommand extends ContainerAwareCommand {

    /**
     * @var EntityManager
     */
    private $em;

    protected function configure() {
        $this->setName('inspector:packages');
    }

    function startswith1($haystack, $needle) {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $this->em = $this->getContainer()->get('doctrine')->getManager();

        foreach ($this->em->getRepository('espendInspectorCoreBundle:InspectorProject')->findAll() as $project) {

            $url = null;

            $package = json_decode(@file_get_contents(sprintf('https://packagist.org/packages/%s.json', $project->getName())), true);

            if($package) {
                if(isset($package['package']['repository']) && preg_match('#/github.com/(.*)$#i', $package['package']['repository'], $result)) {
                    $url = 'https://github.com/'. trim($result[1], '.git') .'/blob/master/%file%#L%line%';
                }
            } else {
                $output->writeln('error ' . $project->getName());
            }

            $project->setSourceUrl($url);
            $project->setDownloads(isset($package['package']['downloads']['total']) ? $package['package']['downloads']['total'] : 0);
        }

        $this->em->flush();

    }

}