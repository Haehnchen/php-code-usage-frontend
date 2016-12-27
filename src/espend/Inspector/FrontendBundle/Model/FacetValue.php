<?php

namespace espend\Inspector\FrontendBundle\Model;

class FacetValue
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var int
     */
    private $count;

    public function __construct(string $key, int $count)
    {
        $this->key = $key;
        $this->count = $count;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}