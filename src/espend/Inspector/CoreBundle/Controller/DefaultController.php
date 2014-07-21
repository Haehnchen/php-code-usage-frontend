<?php

namespace espend\Inspector\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {

        $em = $this->getDoctrine()->getManager();
        $em->getRepository('')

        $payment = $em->find('', $result->getInvId());

        return $this->render('espendInspectorCoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
