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
                'view' => 'Type Hinting',
            ),
            'doc' => array(
                'internal' => 'doc_type',
                'view' => 'Docblock Typ',
            ),
            'annotation' => array(
                'internal' => 'annotation',
                'view' => 'Annotation',
            ),
            'use' => array(
                'internal' => 'use',
                'view' => 'Use Import',
            ),
            'instanceof' => array(
                'internal' => 'instanceof',
                'view' => 'Use in instanceof',
            ),
        );

        $name = $request->query->get('name');
        if (!isset($map[$request->query->get('type')])) {
            throw $this->createNotFoundException('type not found');
        }

        $type = $map[$request->query->get('type')]['internal'];

        $dynamics = $this->get('espend_inspector_frontend.repository.usage_repository')->findUsage($name, $type);

        /** @var InspectorDynamic[] $dynamics */
       // $dynamics = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1));

        return array(
            'class' => $this->get('espend_inspector_frontend.repository.class')->findByClass($name),
            'dynamics' => $dynamics,
            'dynamic_name' => $map[$request->query->get('type')]['view'],
        );

    }
}
