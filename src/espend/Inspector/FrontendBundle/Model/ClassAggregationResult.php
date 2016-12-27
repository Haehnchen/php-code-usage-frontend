<?php

namespace espend\Inspector\FrontendBundle\Model;

class ClassAggregationResult
{
    /**
     * @var FacetValue[]
     */
    private $methods;

    /**
     * @var FacetValue[]
     */
    private $types;

    public function __construct(array $types = [], array $methods = [])
    {
        $this->methods = $methods;
        $this->types = $types;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return FacetValue[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @param string $typeName
     * @return int
     */
    public function getTypeCount(string $typeName) : int
    {
        foreach($this->types as $type) {
            if($type->getKey() === $typeName) {
                return $type->getCount();
            }
        }

        return -1;
    }
}