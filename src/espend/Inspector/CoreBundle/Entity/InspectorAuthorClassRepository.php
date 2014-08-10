<?php

namespace espend\Inspector\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class InspectorAuthorClassRepository extends EntityRepository
{

    public function getHitList($limit = 8) {
        $qb = $this->createQueryBuilder('authorClass');

        $qb->join('authorClass.author', 'author');
        $qb->groupBy('author.id');
        $qb->select(array(
            'author.name',
            'count(authorClass.class) as total'
        ));

        $qb->orderBy('total', 'DESC');
        $qb->setMaxResults($limit);

        return $qb->getQuery();
    }

}
