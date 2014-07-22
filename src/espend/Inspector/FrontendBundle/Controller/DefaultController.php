<?php

namespace espend\Inspector\FrontendBundle\Controller;

use Doctrine\ORM\EntityRepository;
use espend\Inspector\CoreBundle\Entity\InspectorClass;
use espend\Inspector\CoreBundle\Entity\InspectorMethod;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    public function indexAction(Request $request)
    {

        if(!$request->query->has("q")) {
            return $this->createNotFoundException();
        }

        return $this->getMatchResponse($request->query->get('q'));

    }

    public function viewAction($view) {
        $view = str_replace('/', '\\', $view);
        return $this->getMatchResponse($view);
    }

    public function sitemapAction() {
        $content = $this->container->get('espend_inspector_frontend.sitemap_generator')->getContent();
        return new Response($content, 200, array(
            'Content-Type' => 'application/xml; charset=utf-8',
        ));
    }

    private function getMatchResponse($name) {
        if (preg_match('#^method:(.*?)$#i', $name, $result)) {
            return $this->forward('espendInspectorFrontendBundle:Method:index', array(), array(
                'name' => $result[1],
            ));
        } else if (preg_match('#^instance:(.*?)$#i', $name, $result)) {
            return $this->forward('espendInspectorFrontendBundle:Instance:index', array(), array(
                'name' => $result[1],
            ));
        } else if (preg_match('#(.*):(.*)#i', $name, $result)) {
            return $this->forward('espendInspectorFrontendBundle:Method:index', array(), array(
                'name' => $name,
            ));
        } else {
            return $this->forward('espendInspectorFrontendBundle:Class:index', array(), array(
                'name' => $name,
            ));
        }

    }

}
