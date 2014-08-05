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
        $inspectorClass = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorClass')->findOneBy(array(
            'class' => $name,
        ));

        if (!$inspectorClass) {
            throw $this->createNotFoundException("class not found");
        }

        $qb = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorMethod')->createQueryBuilder('method');
        $qb->andWhere('method.class = :class');
        $qb->setParameter('class', $inspectorClass->getId());

        $qb->setMaxResults(5);

        /** @var InspectorMethod[] $methods */
        $methods = $qb->getQuery()->getResult();

        $qb = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorInstance')->createQueryBuilder('inst');
        $qb->andWhere('inst.class = :class');
        $qb->setParameter('class', $inspectorClass->getId());

        $qb->setMaxResults(5);

        /** @var InspectorMethod[] $instances */
        $instances = $qb->getQuery()->getResult();

        $qb = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorSuper')->createQueryBuilder('super');
        $qb->andWhere('super.class = :class');
        $qb->join('super.class', 'class');
        $qb->addSelect('class');
        $qb->setParameter('class', $inspectorClass->getId());

        $qb->setMaxResults(5);

        /** @var InspectorSuper[] $supers */
        $supers = $qb->getQuery()->getResult();



        return array(
            'class' => $inspectorClass,
            'methods' => $methods,
            'instances' => $instances,
            'supers' => $supers,
        );
    }
}
