<?php

namespace espend\Inspector\FrontendBundle\Controller;


use espend\Inspector\CoreBundle\Entity\InspectorClass;
use espend\Inspector\FrontendBundle\Form\HomeFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

    public function indexAction(Request $request) {


        if($request->query->has('q')) {
            return $this->getSearchResponse($request, $request->query->get('q'), $this->createForm(new HomeFormType(), null, array(
                'action' => $this->generateUrl('espend_inspector_frontend_home_post'),
                'method' => 'GET',
            )));
        }

        $form = $this->createForm(new HomeFormType(), null, array(
          'action' => $this->generateUrl('espend_inspector_frontend_home_post'),
          'method' => 'GET',
        ));

        return $this->render('espendInspectorFrontendBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
            'authors' => $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorAuthorClass')->getHitList()->useResultCache(true, 86400)->getArrayResult(),
            'top_classes' => $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorSuper')->getHistList()->useResultCache(true, 86400)->getArrayResult(),
        ));

    }

    public function indexPostAction(Request $request) {

        $form = $this->createForm(new HomeFormType(), null, array(
          'action' => $this->generateUrl('espend_inspector_frontend_home_post'),
          'method' => 'GET',
        ));

        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->render('espendInspectorFrontendBundle:Default:index.html.twig', array(
              'form' => $form->createView(),
            ));
        }

        $data = $form->get('q')->getData();

        return $this->getSearchResponse($request, $data, $form);

    }

    public function viewAction(Request $request, $view, $page = 1) {
        $view = str_replace('/', '\\', $view);
        return $this->getMatchResponse($request, $view);
    }

    public function sitemapAction() {
        $content = $this->container->get('espend_inspector_frontend.sitemap_generator')->getContent();
        return new Response($content, 200, array(
          'Content-Type' => 'application/xml; charset=utf-8',
        ));
    }

    private function getMatchResponse(Request $request, $name) {
        if (preg_match('#^method:(.*?)$#i', $name, $result)) {
            return $this->forward('espendInspectorFrontendBundle:Method:index', array(), array(
              'name' => $result[1],
              'page' => $request->query->get('page', 1),
            ));
        } else if (preg_match('#^instance:(.*?)$#i', $name, $result)) {
            return $this->forward('espendInspectorFrontendBundle:Instance:index', array(), array(
              'name' => $result[1],
              'page' => $request->query->get('page', 1),
            ));
        } else if (preg_match('#^(hint|annotation|doc|use):(.*?)$#i', $name, $result)) {
            return $this->forward('espendInspectorFrontendBundle:Dynamic:index', array(), array(
                'name' => $result[2],
                'type' => $result[1],
                'page' => $request->query->get('page', 1),
            ));
        } else if (preg_match('#(.*):(.*)#i', $name, $result)) {
            return $this->forward('espendInspectorFrontendBundle:Method:index', array(), array(
              'name' => $name,
              'page' => $request->query->get('page', 1),
            ));
        } else {
            return $this->forward('espendInspectorFrontendBundle:Class:index', array(), array(
              'name' => $name,
              'page' => $request->query->get('page', 1),
            ));
        }

    }

    private function getSearchResponse(Request $request, $searchQuery, FormInterface $form) {

        if(strlen($searchQuery) > 50) {
            return $this->render('espendInspectorFrontendBundle:Default:index.html.twig', array(
                'error' => 'oops, invalid search...',
                'form' => $form->createView(),
                'authors' => $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorAuthorClass')->getHitList()->useResultCache(true, 86400)->getArrayResult(),
                'top_classes' => $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorSuper')->getHistList()->useResultCache(true, 86400)->getArrayResult(),
            ));
        }

        $qb = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorClass')->createQueryBuilder('class');

        if(preg_match('#^author:(.*)$#i', $searchQuery, $result)) {
            $qb->join('class.author_class', 'author_class');
            $qb->join('author_class.author', 'author');
            $qb->andWhere('author.name = :author_name');
            $qb->setParameter('author_name', $result[1]);

        } else {
            $data = explode(' ', preg_replace('#(\s+)#', ' ', $searchQuery));
            $expr = $qb->expr()->andX();

            foreach ($data as $q) {
                if (strlen($q) > 2) {
                    $expr->add($qb->expr()->like('class.class', $qb->expr()->literal('%' . $q . '%')));
                }
            }

            if ($expr->count() == 0) {
                return $this->render('espendInspectorFrontendBundle:Default:index.html.twig', array(
                    'error' => 'oops, invalid search...',
                    'form' => $form->createView(),
                    'authors' => $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorAuthorClass')->getHitList()->useResultCache(true, 86400)->getArrayResult(),
                    'top_classes' => $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorSuper')->getHistList()->useResultCache(true, 86400)->getArrayResult(),
                ));
            }

            $qb->andWhere($expr);
        }


        $qbCount = clone $qb;

        $qb->leftJoin('class.file', 'file');
        $qb->leftJoin('class.project', 'project');
        $qb->addSelect('project');
        $qb->addSelect('file');
        $qb->addOrderBy('class.weight', 'DESC');
        $qb->addOrderBy('class.class');

        /** @var InspectorClass[] $result */
        $result = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1));

        return $this->render('espendInspectorFrontendBundle:Default:indexPost.html.twig', array(
            'search_name' => $searchQuery,
            'results' => $result,
            'result_count' => $qbCount->select('count(class.id)')->getQuery()->getSingleScalarResult(),
        ));
    }

}
