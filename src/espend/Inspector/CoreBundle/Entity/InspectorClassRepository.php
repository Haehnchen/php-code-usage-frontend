<?php

namespace espend\Inspector\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class InspectorClassRepository extends EntityRepository
{

    public function getClassWithProject($className) {
        $qb = $this->createQueryBuilder('class');

        $qb->leftJoin('class.file', 'file');
        $qb->leftJoin('file.project', 'project');
        $qb->addSelect('file');
        $qb->addSelect('project');

        $qb->andWhere('class.class = :class');
        $qb->setParameter('class', $className);

        return $qb->getQuery()->getOneOrNullResult();
    }

}
