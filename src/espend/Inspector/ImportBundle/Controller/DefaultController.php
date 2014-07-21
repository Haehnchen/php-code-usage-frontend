<?php

namespace espend\Inspector\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('espendInspectorImportBundle:Default:index.html.twig', array('name' => $name));
    }
}
