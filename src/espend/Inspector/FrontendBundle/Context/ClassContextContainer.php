<?php

namespace espend\Inspector\FrontendBundle\Context;

use Doctrine\ORM\EntityManager;
use espend\Inspector\CoreBundle\Entity\InspectorClass;
use espend\Inspector\FrontendBundle\Model\ClassAggregationResult;
use espend\Inspector\FrontendBundle\Repository\ClassRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Exception\RuntimeException;

class ClassContextContainer
{

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $stack;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    private $cache = array();

    /**
     * @var ClassRepository
     */
    private $classRepository;

    /**
     * @var ClassAggregationResult
     */
    private $result;

    public function __construct(RequestStack $stack, EntityManager $em, ClassRepository $classRepository)
    {
        $this->stack = $stack;
        $this->em = $em;
        $this->classRepository = $classRepository;
    }

    public function getClassName()
    {

        $name = null;
        if ($this->stack->getMasterRequest()->query->has('q')) {
            $name = $this->stack->getMasterRequest()->query->get('q');
        }

        if ($this->stack->getMasterRequest()->attributes->has('view')) {
            $name = str_replace('/', '\\', $this->stack->getMasterRequest()->attributes->get('view'));
        }

        if (!$name) {
            throw new RuntimeException('invalid context');
        }

        if (preg_match('#^(?:method|instance|hint|annotation|doc|use|instanceof):(.*?)$#i', $name, $result)) {
            return $result[1];
        } else {
            if (preg_match('#(.*):(.*)#i', $name, $result)) {
                return $result[1];
            }
        }

        return $name;

    }

    /**
     * @return InspectorClass
     */
    public function getClass()
    {
        if (array_key_exists(__FUNCTION__, $this->cache)) {
            return $this->cache[__FUNCTION__];
        }

        return $this->cache[__FUNCTION__] = $this->classRepository->findByClass($this->getClassName());
    }

    public function getChildrenClasses()
    {

    }

    /**
     * @return InspectorClass[]
     */
    public function getSubClasses()
    {
        if (isset($this->cache[__FUNCTION__])) {
            return $this->cache[__FUNCTION__];
        }

        return $this->cache[__FUNCTION__] = $this->classRepository->findSubClasses($this->getClassName());
    }

    function getClassMethods()
    {
        return $this->getResult()->getMethods();
    }

    function usage(string $type)
    {
        return $this->getResult()->getTypeCount($type);
    }

    function getInstanceCount()
    {
        return $this->getResult()->getTypeCount('instanceof');
    }

    function getMethodCount()
    {
        return $this->getResult()->getTypeCount('method');
    }

    private function getResult() : ClassAggregationResult
    {
        if ($this->result) {
            return $this->result;
        }

        return $this->result = $this->classRepository->findClassUsages($this->getClassName());
    }
}