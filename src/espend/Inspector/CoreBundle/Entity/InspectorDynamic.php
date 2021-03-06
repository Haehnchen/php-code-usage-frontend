<?php

namespace espend\Inspector\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InspectorDynamic
 */
class InspectorDynamic
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var array
     */
    private $context;

    /**
     * @var integer
     */
    private $line;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \DateTime
     */
    private $last_found_at;

    /**
     * @var \espend\Inspector\CoreBundle\Entity\InspectorFile
     */
    private $file;

    /**
     * @var string
     */
    private $name;

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
     * Set context
     *
     * @param array $context
     * @return InspectorDynamic
     */
    public function setContext(array $context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return array 
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set line
     *
     * @param integer $line
     * @return InspectorDynamic
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
     * Set key
     *
     * @param string $key
     * @return InspectorDynamic
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
     * Set type
     *
     * @param string $type
     * @return InspectorDynamic
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set last_found_at
     *
     * @param \DateTime $lastFoundAt
     * @return InspectorDynamic
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
     * Set file
     *
     * @param \espend\Inspector\CoreBundle\Entity\InspectorFile $file
     * @return InspectorDynamic
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
     * @var \espend\Inspector\CoreBundle\Entity\InspectorClass
     */
    private $class;


    /**
     * Set class
     *
     * @param \espend\Inspector\CoreBundle\Entity\InspectorClass $class
     * @return InspectorDynamic
     */
    public function setClass(\espend\Inspector\CoreBundle\Entity\InspectorClass $class = null)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return \espend\Inspector\CoreBundle\Entity\InspectorClass 
     */
    public function getClass()
    {
        return $this->class;
    }
    /**
     * @var integer
     */
    private $weight = 0;


    /**
     * Set weight
     *
     * @param integer $weight
     * @return InspectorDynamic
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

    public function setName(string $name) : InspectorDynamic
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
