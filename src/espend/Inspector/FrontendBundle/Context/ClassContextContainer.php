<?php

namespace espend\Inspector\FrontendBundle\Context;

use Doctrine\ORM\EntityManager;
use espend\Inspector\CoreBundle\Entity\InspectorClass;
use Symfony\Component\HttpFoundation\RequestStack;

class ClassContextContainer {

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $stack;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function __construct(RequestStack $stack, EntityManager $em) {
        $this->stack = $stack;
        $this->em = $em;
    }

    public function getClassName() {

        if(!$this->stack->getMasterRequest()->query->has('q')) {
            return null;
        }

        $name = $this->stack->getMasterRequest()->query->get('q');

        if (preg_match('#^(?:method|instance):(.*?)$#i', $name, $result)) {
            return $result[1];
        } else if (preg_match('#(.*):(.*)#i', $name, $result)) {
            return $result[1];
        }

        return $name;

    }

    public function getClass() {
        return $this->em->getRepository('espendInspectorCoreBundle:InspectorClass')->findOneBy(array(
           'class' => $this->getClassName(),
        ));
    }

    /**
     * @return InspectorClass[]
     */
    public function getSubClasses() {
        $inspectorSupers = $this->em->getRepository('espendInspectorCoreBundle:InspectorSuper')->findBy(array(
            'child' => $this->getClass()->getId(),
        ));

        $child = array();
        foreach($inspectorSupers as $super) {
            $child[] = $super->getClass();
        }

        return $child;
    }

    function getSubClassesId() {
        return $this->em->getRepository('espendInspectorCoreBundle:InspectorSuper')->getSubClassesIds($this->getClass()->getId());
    }

    function getClassMethods() {
        return $this->em->getRepository('espendInspectorCoreBundle:InspectorMethod')->getClassMethods($this->getSubClassesId());
    }

    function getInstanceCount() {
        return $this->em->getRepository('espendInspectorCoreBundle:InspectorInstance')->getClassCount($this->getSubClassesId());
    }

    function getMethodCount() {
        return $this->em->getRepository('espendInspectorCoreBundle:InspectorMethod')->getClassCount($this->getSubClassesId());
    }
} 