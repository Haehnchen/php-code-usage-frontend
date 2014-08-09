<?php

namespace espend\Inspector\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InspectorInstance
 */
class InspectorInstance
{
    /**
     * @var integer
     */
    private $id;


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
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $context;

    /**
     * @var \espend\Inspector\CoreBundle\Entity\InspectorFile
     */
    private $file;


    /**
     * Set class
     *
     * @param string $class
     * @return InspectorInstance
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
     * Set context
     *
     * @param string $context
     * @return InspectorInstance
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string 
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set file
     *
     * @param \espend\Inspector\CoreBundle\Entity\InspectorFile $file
     * @return InspectorInstance
     */
    public function setFile(\espend\Inspector\CoreBundle\Entity\InspectorFile $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \espend\Inspector\CoreBundle\Entity\InspectorFile 
     */
    public function getFile()
    {
        return $this->file;
    }
    /**
     * @var \DateTime
     */
    private $last_found_at;


    /**
     * Set last_found_at
     *
     * @param \DateTime $lastFoundAt
     * @return InspectorInstance
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
    /**
     * @var integer
     */
    private $line;


    /**
     * Set line
     *
     * @param integer $line
     * @return InspectorInstance
     */
    public function setLine($line)
    {
        $this->line = $line;

        return $this;
    }

    /**
     * Get line
     *
     * @return integer 
     */
    public function getLine()
    {
        return $this->line;
    }
    /**
     * @var string
     */
    private $key;


    /**
     * Set key
     *
     * @param string $key
     * @return InspectorInstance
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string 
     */
    public function getKey()
    {
        return $this->key;
    }
    /**
     * @var integer
     */
    private $weight = 0;


    /**
     * Set weight
     *
     * @param integer $weight
     * @return InspectorInstance
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer 
     */
    public function getWeight()
    {
        return $this->weight;
    }
}
