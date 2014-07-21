<?php

namespace espend\Inspector\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InspectorSuper
 */
class InspectorSuper
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
    private $child;

    /**
     * @var string
     */
    private $super_type;

    /**
     * @var \espend\Inspector\CoreBundle\Entity\InspectorFile
     */
    private $file;


    /**
     * Set class
     *
     * @param string $class
     * @return InspectorSuper
     */
    public function setClass($class)
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
     * Set child
     *
     * @param \espend\Inspector\CoreBundle\Entity\InspectorClass
     * @return InspectorSuper
     */
    public function setChild(InspectorClass $child)
    {
        $this->child = $child;

        return $this;
    }

    /**
     * Get child
     *
     * @return \espend\Inspector\CoreBundle\Entity\InspectorClass
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * Set super_type
     *
     * @param string $superType
     * @return InspectorSuper
     */
    public function setSuperType($superType)
    {
        $this->super_type = $superType;

        return $this;
    }

    /**
     * Get super_type
     *
     * @return string 
     */
    public function getSuperType()
    {
        return $this->super_type;
    }

    /**
     * Set file
     *
     * @param \espend\Inspector\CoreBundle\Entity\InspectorFile $file
     * @return InspectorSuper
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
     * @return InspectorSuper
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
