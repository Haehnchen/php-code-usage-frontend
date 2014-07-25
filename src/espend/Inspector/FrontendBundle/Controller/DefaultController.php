<?php

namespace espend\Inspector\FrontendBundle\Controller;


use espend\Inspector\CoreBundle\Entity\InspectorClass;
use espend\Inspector\FrontendBundle\Form\HomeFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

    public function indexAction(Request $request) {

        $form = $this->createForm(new HomeFormType(), null, array(
            'action' => $this->generateUrl('espend_inspector_frontend_home_post')
        ));

        return $this->render('espendInspectorFrontendBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function indexPostAction(Request $request) {

        $form = $this->createForm(new HomeFormType(), null, array(
          'action' => $this->generateUrl('espend_inspector_frontend_home_post')
        ));

        $form->handleRequest($request);

        if(!$form->isValid()) {
            return $this->render('espendInspectorFrontendBundle:Default:index.html.twig', array(
              'form' => $form->createView(),
            ));
        }


        $qb = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorClass')->createQueryBuilder('class');
        $qb->andWhere($qb->expr()->like('class.class', ':query'));
        $qb->setParameter('query', '%' . $form->get('q')->getData() . '%');
        $qb->setMaxResults(30);

        /** @var InspectorClass[] $result */
        $result = $qb->getQuery()->getResult();

        return $this->render('espendInspectorFrontendBundle:Default:indexPost.html.twig', array(
          'search_name' => $form->get('q')->getData(),
          'results' => $result,
        ));

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
