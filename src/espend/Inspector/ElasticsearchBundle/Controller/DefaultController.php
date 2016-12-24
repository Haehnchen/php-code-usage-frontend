<?php

namespace espend\Inspector\ElasticsearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('espendInspectorElasticsearchBundle:Default:index.html.twig');
    }
}
