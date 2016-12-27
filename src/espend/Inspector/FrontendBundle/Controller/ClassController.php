<?php

namespace espend\Inspector\FrontendBundle\Controller;

use espend\Inspector\CoreBundle\Entity\InspectorInstance;
use espend\Inspector\CoreBundle\Entity\InspectorMethod;
use espend\Inspector\CoreBundle\Entity\InspectorSuper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ClassController extends Controller
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

        if (!$inspectorClass = $this->get('espend_inspector_frontend.repository.class')->findByClass($name)) {
            throw $this->createNotFoundException("class not found");
        }

        return [
            'class' => $inspectorClass,
        ];
    }
}
