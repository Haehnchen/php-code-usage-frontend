<?php

namespace espend\Inspector\ImportBundle\Command;

use espend\Inspector\CoreBundle\Entity\InspectorDynamic;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanupCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('inspector:cleanup');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $em = $this->getContainer()->get('doctrine')->getManager();

        $qb = $em->getRepository('espendInspectorCoreBundle:InspectorDynamic')->createQueryBuilder('dynamic')->delete();
        $qb->andWhere('dynamic.last_found_at < :last_found_at');
        $qb->setParameter('last_found_at', date_create());
        $qb->getQuery()->execute();

        $qb1 = $em->getRepository('espendInspectorCoreBundle:InspectorMethod')->createQueryBuilder('method')->delete();
        $qb1->andWhere('method.last_found_at < :last_found_at');
        $qb1->setParameter('last_found_at', date_create());
        $qb1->getQuery()->execute();

        $qb2 = $em->getRepository('espendInspectorCoreBundle:InspectorAuthor')->createQueryBuilder('author')->delete();
        $qb2->andWhere('author.last_found_at < :last_found_at');
        $qb2->setParameter('last_found_at', date_create());
        $qb2->getQuery()->execute();

    }

}