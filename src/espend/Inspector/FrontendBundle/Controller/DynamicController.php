<?php

namespace espend\Inspector\FrontendBundle\Controller;

use espend\Inspector\CoreBundle\Entity\InspectorDynamic;
use espend\Inspector\CoreBundle\Entity\InspectorInstance;
use espend\Inspector\CoreBundle\Entity\InspectorMethod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DynamicController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction(Request $request)
    {

        if (!$request->query->has('name') || !$request->query->has('type')) {
            throw $this->createNotFoundException();
        }

        $map = array(
            'hint' => array(
                'internal' => 'type_hint',
                'view' => 'Type Hint',
            ),
            'doc' => array(
                'internal' => 'doc_type',
                'view' => 'Doc Typ',
            ),
            'annotation' => array(
                'internal' => 'annotation',
                'view' => 'Type annotation',
            ),
            'use' => array(
                'internal' => 'use',
                'view' => 'Import Usage',
            ),
        );

        $name = $request->query->get('name');
        $type = $map[$request->query->get('type')]['internal'];

        $inspectorClass = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorClass')->findOneBy(array(
            'class' => $name,
        ));

        if (!$inspectorClass) {
            throw $this->createNotFoundException();
        }

        $qb = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorDynamic')->createQueryBuilder('dynamic');
        $qb->leftJoin('dynamic.class', 'class');
        $qb->leftJoin('class.file', 'file');
        $qb->leftJoin('file.project', 'project');

        $qb->addSelect('project');
        $qb->addSelect('file');
        $qb->addSelect('class');

        $qb->andWhere($qb->expr()->in('dynamic.class', array($inspectorClass->getId())));
        $qb->andWhere('dynamic.type = :type');
        $qb->setParameter('type', $type);

        $qb->addOrderBy('dynamic.weight', 'DESC');
        $qb->addOrderBy('file.name');

        /** @var InspectorDynamic[] $dynamics */
        $dynamics = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1));

        return array(
            'class' => $inspectorClass,
            'dynamics' => $dynamics,
            'dynamic_name' => $map[$request->query->get('type')]['view'],
        );

    }
}
