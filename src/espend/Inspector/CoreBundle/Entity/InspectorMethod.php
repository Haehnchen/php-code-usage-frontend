<?php

namespace espend\Inspector\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InspectorMethod
 */
class InspectorMethod
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
    private $context;

    /**
     * @var \espend\Inspector\CoreBundle\Entity\InspectorFile
     */
    private $file;


    /**
     * Set context
     *
     * @param string $context
     * @return InspectorMethod
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
     * @return InspectorMethod
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
     * @var string
     */
    private $class;


    /**
     * Set class
     *
     * @param string $class
     * @return InspectorMethod
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return InspectorClass
     */
    public function getClass()
    {
        return $this->class;
    }
    /**
     * @var string
     */
    private $method;


    /**
     * Set method
     *
     * @param string $method
     * @return InspectorMethod
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return string 
     */
    public function getMethod()
    {
        return $this->method;
    }
    /**
     * @var string
     */
    private $hash;


    /**
     * Set hash
     *
     * @param string $hash
     * @return InspectorMethod
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }
    /**
     * @var \DateTime
     */
    private $last_found_at;


    /**
     * Set last_found_at
     *
     * @param \DateTime $lastFoundAt
     * @return InspectorMethod
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
     * @return InspectorMethod
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
     * @return InspectorMethod
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
     * @return InspectorMethod
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
