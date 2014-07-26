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
     * @var \espend\Inspector\CoreBundle\Entity\InspectorProject
     */
    private $project;


    /**
     * Set project
     *
     * @param \espend\Inspector\CoreBundle\Entity\InspectorProject $project
     * @return InspectorClass
     */
    public function setProject(\espend\Inspector\CoreBundle\Entity\InspectorProject $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \espend\Inspector\CoreBundle\Entity\InspectorProject 
     */
    public function getProject()
    {
        return $this->project;
    }
}
