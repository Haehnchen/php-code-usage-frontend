<?php

namespace espend\Inspector\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class InspectorProjectRepository extends EntityRepository
{

    public function getMaxDownloads() {

        $qb = $this->createQueryBuilder('inst');
        $qb->select('max(inst.downloads)');

        return $qb->getQuery()->getSingleScalarResult();
    }


}
