<?php

namespace espend\Inspector\FrontendBundle\Context;

use Doctrine\ORM\EntityManager;
use espend\Inspector\CoreBundle\Entity\InspectorClass;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Exception\RuntimeException;

class ClassContextContainer {

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $stack;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    private $cache = array();

    public function __construct(RequestStack $stack, EntityManager $em) {
        $this->stack = $stack;
        $this->em = $em;
    }

    public function getClassName() {

        $name = null;
        if($this->stack->getMasterRequest()->query->has('q')) {
            $name = $this->stack->getMasterRequest()->query->get('q');
        }

        if ($this->stack->getMasterRequest()->attributes->has('view')) {
            $name = str_replace('/', '\\', $this->stack->getMasterRequest()->attributes->get('view'));
        }

        if(!$name) {
            throw new RuntimeException('invalid context');
        }

        if (preg_match('#^(?:method|instance|hint|annotation|doc|use):(.*?)$#i', $name, $result)) {
            return $result[1];
        } else if (preg_match('#(.*):(.*)#i', $name, $result)) {
            return $result[1];
        }

        return $name;

    }

    public function getClass() {
        return array_key_exists(__FUNCTION__, $this->cache) != null ? $this->cache[__FUNCTION__] : $this->cache[__FUNCTION__] = $this->em->getRepository('espendInspectorCoreBundle:InspectorClass')->findOneBy(array(
           'class' => $this->getClassName(),
        ));
    }

    public function getChildrenClasses() {

    }

    /**
     * @return InspectorClass[]
     */
    public function getSubClasses() {

        $qb = $this->em->getRepository('espendInspectorCoreBundle:InspectorSuper')->createQueryBuilder('supers');
        $qb->join('supers.class', 'parentClass');
        $qb->addSelect('parentClass');
        $qb->andWhere('supers.child = :child');
        $qb->setParameter('child', $this->getClass()->getId());
        $qb->groupBy('parentClass.id');
        $qb->addOrderBy('parentClass.weight', 'DESC');
        $qb->addOrderBy('parentClass.class');

        /** @var InspectorClass[] $inspectorSupers */
        $inspectorSupers = $qb->getQuery()->getResult();

        // @TODO: remove; foreign
        $child = array();
        foreach($inspectorSupers as $super) {
            $child[] = $super->getClass();
        }

        return array_key_exists(__FUNCTION__, $this->cache) != null ? $this->cache[__FUNCTION__] : $this->cache[__FUNCTION__] = $child;
    }

    function getSubClassesId() {
        return array_key_exists(__FUNCTION__, $this->cache) != null ? $this->cache[__FUNCTION__] : $this->cache[__FUNCTION__] = $this->em->getRepository('espendInspectorCoreBundle:InspectorSuper')->getSubClassesIds($this->getClass()->getId());
    }

    function getClassMethods() {
        return array_key_exists(__FUNCTION__, $this->cache) != null ? $this->cache[__FUNCTION__] : $this->cache[__FUNCTION__] = $this->em->getRepository('espendInspectorCoreBundle:InspectorMethod')->getClassMethods($this->getSubClassesId());
    }

    function getInstanceCount() {
        return array_key_exists(__FUNCTION__, $this->cache) != null ? $this->cache[__FUNCTION__] : $this->cache[__FUNCTION__] = $this->em->getRepository('espendInspectorCoreBundle:InspectorInstance')->getClassCount($this->getClass()->getId());
    }

    function getMethodCount() {
        return array_key_exists(__FUNCTION__, $this->cache) != null ? $this->cache[__FUNCTION__] : $this->cache[__FUNCTION__] = $this->em->getRepository('espendInspectorCoreBundle:InspectorMethod')->getClassCount($this->getSubClassesId());
    }

    function getDynamicCount() {
        return array_key_exists(__FUNCTION__, $this->cache) != null ? $this->cache[__FUNCTION__] : $this->cache[__FUNCTION__] = $this->em->getRepository('espendInspectorCoreBundle:InspectorDynamic')->getClassCounts($this->getClass()->getId());
    }
} 