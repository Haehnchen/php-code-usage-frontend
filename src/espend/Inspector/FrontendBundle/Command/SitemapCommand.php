<?php

namespace espend\Inspector\FrontendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SitemapCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('inspector:sitemap');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $context = $this->getContainer()->get('router')->getContext();
        $context->setHost('code-usage.com');
        $context->setScheme('http');

        $filename = dirname($this->getContainer()->get('kernel')->getRootDir()) . '/web/sitemap.xml';
        $content = $this->getContainer()->get('espend_inspector_frontend.sitemap_generator')->getContent();
        file_put_contents($filename, $content);
    }

} 