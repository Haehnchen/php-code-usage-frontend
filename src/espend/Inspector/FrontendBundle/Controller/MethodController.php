<?php

namespace espend\Inspector\FrontendBundle\Controller;

use espend\Inspector\CoreBundle\Entity\InspectorMethod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MethodController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction(Request $request)
    {
        if(!$request->query->has('name')) {
            throw $this->createNotFoundException();
        }

        $name = $request->query->get('name');

        if (preg_match('#(.*):(.*)#i', $name, $result)) {
            if (!$class = $this->get('espend_inspector_frontend.repository.class')->findByClass($result[1])) {
                throw $this->createNotFoundException('Class not found');
            }

            $methods = $this->get('espend_inspector_frontend.repository.usage_repository')
                ->findMethodUsage($class->getClass(), $result[2]);

            //$methods = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1));

            return [
                'class' => $class,
                'methods' => $methods,
                'method_name' => $result[2],
            ];
        }

        if (!$class = $this->get('espend_inspector_frontend.repository.class')->findByClass($name)) {
           throw $this->createNotFoundException('Class not found');
        }

        $methods = $this->get('espend_inspector_frontend.repository.usage_repository')->findUsage($name, 'method');


        /** @var InspectorMethod[] $methods */
       // $methods = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1));

        return array(
            'class' => $class,
            'methods' => $methods,
        );

    }
}
