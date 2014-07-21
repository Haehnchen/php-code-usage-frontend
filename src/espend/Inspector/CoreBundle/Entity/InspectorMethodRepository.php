<?php

namespace espend\Inspector\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class InspectorMethodRepository extends EntityRepository
{

    public function getClassCount($class_ids) {

        $qb = $this->createQueryBuilder('inst');

        $qb->select('count(inst.id)');
        $qb->andWhere($qb->expr()->in('inst.class', (array) $class_ids));

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getClassMethods(array $class_ids) {

        $qb = $this->createQueryBuilder('method');
        $qb->join('method.class', 'class');
        $qb->andWhere($qb->expr()->in('method.class', $class_ids));
        $qb->groupBy('method.method');
        $qb->select(array(
            'method.method',
            'class.class',
        ));

        return $qb->getQuery()->getArrayResult();
    }

}
