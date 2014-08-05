<?php

namespace espend\Inspector\FrontendBundle\Controller;

use espend\Inspector\CoreBundle\Entity\InspectorInstance;
use espend\Inspector\CoreBundle\Entity\InspectorMethod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InstanceController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction(Request $request)
    {

        if (!$request->query->has('name')) {
            throw $this->createNotFoundException();
        }

        $name = $request->query->get('name');

        $inspectorClass = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorClass')->findOneBy(array(
            'class' => $name,
        ));

        if (!$inspectorClass) {
            throw $this->createNotFoundException();
        }

        $qb = $this->getDoctrine()->getRepository('espendInspectorCoreBundle:InspectorInstance')->createQueryBuilder('inst');
        $qb->andWhere('inst.class = :class');
        $qb->setParameter('class', $inspectorClass->getId());

        $qb->setMaxResults(5);

        /** @var InspectorMethod[] $instances */
        $instances = $qb->getQuery()->getResult();

        return array(
            'class' => $inspectorClass,
            'instances' => $instances,
        );

    }
}
