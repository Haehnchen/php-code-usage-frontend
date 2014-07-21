<?php

namespace espend\Inspector\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InspectorMethodChild
 */
class InspectorMethodChild
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $class;

    /**
     * @var \espend\Inspector\CoreBundle\Entity\InspectorMethod
     */
    private $method;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set class
     *
     * @param string $class
     * @return InspectorMethodChild
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return string 
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set method
     *
     * @param \espend\Inspector\CoreBundle\Entity\InspectorMethod $method
     * @return InspectorMethodChild
     */
    public function setMethod(\espend\Inspector\CoreBundle\Entity\InspectorMethod $method = null)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return \espend\Inspector\CoreBundle\Entity\InspectorMethod 
     */
    public function getMethod()
    {
        return $this->method;
    }
    /**
     * @var \DateTime
     */
    private $last_found_at;


    /**
     * Set last_found_at
     *
     * @param \DateTime $lastFoundAt
     * @return InspectorMethodChild
     */
    public function setLastFoundAt($lastFoundAt)
    {
        $this->last_found_at = $lastFoundAt;

        return $this;
    }

    /**
     * Get last_found_at
     *
     * @return \DateTime 
     */
    public function getLastFoundAt()
    {
        return $this->last_found_at;
    }
}
