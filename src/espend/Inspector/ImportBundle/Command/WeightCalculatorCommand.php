<?php

namespace espend\Inspector\ImportBundle\Command;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use espend\Inspector\CoreBundle\Entity\InspectorDynamic;
use espend\Inspector\CoreBundle\Entity\InspectorProject;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WeightCalculatorCommand extends ContainerAwareCommand {

    /**
     * @var EntityManager
     */
    private $em;

    private $pWeights = array();

    protected function configure() {
        $this->setName('inspector:calc:weight');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $this->em = $this->getContainer()->get('doctrine')->getManager();
        $this->em->getConfiguration()->setSQLLogger(null);

        /** @var Connection $connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();
        $max = $this->em->getRepository('espendInspectorCoreBundle:InspectorProject')->getMaxDownloads();

        foreach ($this->em->getRepository('espendInspectorCoreBundle:InspectorProject')->findAll() as $project) {
            $this->pWeights[$project->getId()] = ($project->getDownloads() / $max) * 100;
        }

        $output->writeln('InspectorClass weight');
        $this->calculateClass($output, $connection, $max);

        $output->writeln('InspectorInstance weight');
        $this->calculateDynamic($this->em->getRepository('espendInspectorCoreBundle:InspectorInstance'), $output, $connection, $max);

        $output->writeln('InspectorMethod weight');
        $this->calculateDynamic($this->em->getRepository('espendInspectorCoreBundle:InspectorMethod'), $output, $connection, $max);

        $output->writeln('InspectorDynamic weight');
        $this->calculateDynamic($this->em->getRepository('espendInspectorCoreBundle:InspectorDynamic'), $output, $connection, $max);

    }

    private function calculateClass(OutputInterface $output, Connection $connection, $max) {

        $inspectorClassRepository = $this->em->getRepository('espendInspectorCoreBundle:InspectorClass');
        $qb = $inspectorClassRepository->createQueryBuilder('class');

        $qb->select(array(
            'class.id',
            'project.downloads as projectDownloads',
            'file.name',
        ));

        $qb->join('class.project', 'project');
        $qb->join('class.file', 'file');

        $iterableResult = $qb->getQuery()->iterate(null, AbstractQuery::HYDRATE_SCALAR);

        $i = 0;
        $connection->beginTransaction();
        foreach ($iterableResult AS $row) {

            $downloads = $row[0]['projectDownloads'];
            $projectScore = ($downloads / $max) * 100;
            $value = round($projectScore * 100);

            if(preg_match('#(Test|Fixture)#i', $row[0]['name'], $foo)) {
                $value = $value * 0.1;
            }

            $qb = $inspectorClassRepository->createQueryBuilder('c')->update();
            $qb->andWhere('c.id = :id');
            $qb->setParameter('id', $row[0]['id']);

            $qb->set('c.weight', $value);
            $qb->getQuery()->execute();

            if (($i++ % 500) == 0) {
                $connection->commit();
                $output->writeln("commit");
                $connection->beginTransaction();
            }

        }

        if ($connection->isTransactionActive()) {
            $connection->commit();
        }

    }

    private function calculateDynamic(EntityRepository $inspectorDynamicRepository, OutputInterface $output, Connection $connection, $max) {

        $qb = $inspectorDynamicRepository->createQueryBuilder('dynamic');
        $qb->join('dynamic.file', 'dynamicFile');
        $qb->join('dynamic.class', 'class');
        $qb->join('class.project', 'projectClass');
        $qb->join('class.file', 'file');
        $qb->join('file.project', 'projectFile');

        $qb->select(array(
            'dynamic.id',
            'projectFile.downloads as projectFileDownloads',
            'projectClass.downloads as projectClassDownloads',
            'dynamicFile.name'
        ));

        $iterableResult = $qb->getQuery()->iterate(null, AbstractQuery::HYDRATE_SCALAR);

        $i = 0;
        $connection->beginTransaction();
        foreach ($iterableResult AS $row) {


            $downloads = $row[0]['projectFileDownloads'] + $row[0]['projectClassDownloads'];
            $projectScore = ($downloads / $max) * 100;

            $value = round($projectScore * 100);
            if (preg_match('#(Test|Fixture)#i', $row[0]['name'], $foo)) {
                $value = $value * 0.1;
            }

            $qb = $inspectorDynamicRepository->createQueryBuilder('dyn')->update();
            $qb->andWhere('dyn.id = :id');
            $qb->setParameter('id', $row[0]['id']);

            $qb->set('dyn.weight', $value);
            $qb->getQuery()->execute();

            if (($i++ % 500) == 0) {
                $connection->commit();
                $output->writeln("commit");
                $connection->beginTransaction();
            }

        }

        if ($connection->isTransactionActive()) {
            $connection->commit();
        }
    }

}