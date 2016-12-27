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

        if (!$class = $this->get('espend_inspector_frontend.repository.class')->findByClass($name)) {
            throw $this->createNotFoundException('Class not found');
        }

        $instances = $this->get('espend_inspector_frontend.repository.usage_repository')
            ->findUsage($class->getClass(), 'instance');

        /** @var InspectorMethod[] $instances */
        //$instances = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1));

        return array(
            'class' => $class,
            'instances' => $instances,
        );

    }
}
