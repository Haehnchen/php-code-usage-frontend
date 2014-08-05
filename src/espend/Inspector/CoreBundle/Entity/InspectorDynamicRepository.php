<?php

namespace espend\Inspector\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class InspectorDynamicRepository extends EntityRepository
{

    public function getClassCounts($id) {
        $qb1 = $this->createQueryBuilder('dynamic');

        $qb1->select(array(
            'count(dynamic.id) as total',
            'dynamic.type',
        ));

        $qb1->andWhere($qb1->expr()->in('dynamic.class', array($id)));
        $qb1->groupBy('dynamic.type');

        return array_column($qb1->getQuery()->getArrayResult(), 'total', 'type');
    }

}
