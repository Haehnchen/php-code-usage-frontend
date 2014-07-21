<?php

namespace espend\Inspector\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InspectorClass
 */
class InspectorClass
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
     * @return InspectorClass
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
     * @var \DateTime
     */
    private $last_found_at;


    /**
     * Set last_found_at
     *
     * @param \DateTime $lastFoundAt
     * @return InspectorClass
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
     * @var \espend\Inspector\CoreBundle\Entity\InspectorFile
     */
    private $file;


    /**
     * Set file
     *
     * @param \espend\Inspector\CoreBundle\Entity\InspectorFile $file
     * @return InspectorClass
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
}
