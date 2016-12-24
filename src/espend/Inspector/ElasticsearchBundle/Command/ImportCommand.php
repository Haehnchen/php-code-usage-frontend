<?php

namespace espend\Inspector\ElasticsearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('inspector:es:import');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('espend_inspector_elasticsearch.index.index_initializer')->init();

        $this->getContainer()->get('espend_inspector_elasticsearch.importer.importer')->import();
    }
}
