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

            $inspectorClass = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorClass')->findOneBy(array(
                'class' => $result[1],
            ));

            if (!$inspectorClass) {
                throw $this->createNotFoundException();
            }

            $class_ids = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorSuper')->getSubClassesIds($inspectorClass->getId());

            $qb = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorMethod')->createQueryBuilder('method');
            $qb->leftJoin('method.class', 'class');
            $qb->leftJoin('method.file', 'file');
            $qb->leftJoin('file.project', 'project');

            $qb->addSelect('project');
            $qb->addSelect('file');
            $qb->addSelect('class');

            $qb->andWhere($qb->expr()->in('method.class', $class_ids));
            $qb->andWhere('method.method = :name');
            $qb->setParameter('name', $result[2]);

            $qb->addOrderBy('method.weight', 'DESC');
            $qb->addOrderBy('class.class');

            $methods = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1));

            return array(
                'class' => $inspectorClass,
                'methods' => $methods,
            );
        }


        $inspectorClass = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorClass')->findOneBy(array(
            'class' => $name,
        ));

        if (!$inspectorClass) {
            throw $this->createNotFoundException();
        }

        $qb = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorMethod')->createQueryBuilder('method');
        $qb->leftJoin('method.class', 'class');
        $qb->leftJoin('method.file', 'file');
        $qb->leftJoin('file.project', 'project');

        $qb->addSelect('project');
        $qb->addSelect('file');
        $qb->addSelect('class');

        $qb->andWhere('method.class = :class');
        $qb->setParameter('class', $inspectorClass->getId());

        /** @var InspectorMethod[] $methods */
        $methods = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1));

        return array(
            'class' => $inspectorClass,
            'methods' => $methods,
        );

    }
}
