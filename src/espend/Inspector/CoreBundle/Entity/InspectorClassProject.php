<?php

namespace espend\Inspector\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InspectorClassProject
 */
class InspectorClassProject
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $last_found_at;

    /**
     * @var \espend\Inspector\CoreBundle\Entity\InspectorProject
     */
    private $project;

    /**
     * @var \espend\Inspector\CoreBundle\Entity\InspectorClass
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
     * Set last_found_at
     *
     * @param \DateTime $lastFoundAt
     * @return InspectorClassProject
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
     * Set project
     *
     * @param \espend\Inspector\CoreBundle\Entity\InspectorProject $project
     * @return InspectorClassProject
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

    /**
     * Set class
     *
     * @param \espend\Inspector\CoreBundle\Entity\InspectorClass $class
     * @return InspectorClassProject
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
}
