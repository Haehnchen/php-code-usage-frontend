<?php

namespace espend\Inspector\FrontendBundle\Controller;

use espend\Inspector\CoreBundle\Entity\InspectorInstance;
use espend\Inspector\CoreBundle\Entity\InspectorMethod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InstanceController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction(Request $request)
    {

        if (!$request->query->has('name')) {
            throw $this->createNotFoundException();
        }

        $name = $request->query->get('name');

        $inspectorClass = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorClass')->findOneBy(array(
            'class' => $name,
        ));

        if (!$inspectorClass) {
            throw $this->createNotFoundException();
        }

        $qb = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorInstance')->createQueryBuilder('inst');
        $qb->andWhere('inst.class = :class');
        $qb->setParameter('class', $inspectorClass->getId());
        $qb->join('inst.class', 'class');
        $qb->join('inst.file', 'file');
        $qb->leftJoin('file.project', 'project');
        $qb->addSelect('file');
        $qb->addSelect('project');

        $qb->addOrderBy('inst.weight', 'DESC');
        $qb->addOrderBy('class.class');

        /** @var InspectorMethod[] $instances */
        $instances = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1));

        return array(
            'class' => $inspectorClass,
            'instances' => $instances,
        );

    }
}
