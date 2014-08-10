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
    /**
     * @var string
     */
    private $doc_comment;


    /**
     * Set doc_comment
     *
     * @param string $docComment
     * @return InspectorClass
     */
    public function setDocComment($docComment)
    {
        $this->doc_comment = $docComment;

        return $this;
    }

    /**
     * Get doc_comment
     *
     * @return string 
     */
    public function getDocComment()
    {
        return $this->doc_comment;
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
    /**
     * @var integer
     */
    private $weight = 0;


    /**
     * Set weight
     *
     * @param integer $weight
     * @return InspectorClass
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
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $author_class;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->author_class = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add author_class
     *
     * @param \espend\Inspector\CoreBundle\Entity\InspectorAuthorClass $authorClass
     * @return InspectorClass
     */
    public function addAuthorClass(\espend\Inspector\CoreBundle\Entity\InspectorAuthorClass $authorClass)
    {
        $this->author_class[] = $authorClass;

        return $this;
    }

    /**
     * Remove author_class
     *
     * @param \espend\Inspector\CoreBundle\Entity\InspectorAuthorClass $authorClass
     */
    public function removeAuthorClass(\espend\Inspector\CoreBundle\Entity\InspectorAuthorClass $authorClass)
    {
        $this->author_class->removeElement($authorClass);
    }

    /**
     * Get author_class
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAuthorClass()
    {
        return $this->author_class;
    }
}
