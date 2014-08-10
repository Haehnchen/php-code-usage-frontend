<?php

namespace espend\Inspector\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InspectorAuthorClass
 */
class InspectorAuthorClass
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $last_found_at;

    /**
     * @var \espend\Inspector\CoreBundle\Entity\InspectorAuthor
     */
    private $author;

    /**
     * @var \espend\Inspector\CoreBundle\Entity\InspectorClass
     */
    private $class;


    /**
     * Set id
     *
     * @param string $id
     * @return InspectorAuthorClass
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set last_found_at
     *
     * @param \DateTime $lastFoundAt
     * @return InspectorAuthorClass
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
     * Set author
     *
     * @param \espend\Inspector\CoreBundle\Entity\InspectorAuthor $author
     * @return InspectorAuthorClass
     */
    public function setAuthor(\espend\Inspector\CoreBundle\Entity\InspectorAuthor $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \espend\Inspector\CoreBundle\Entity\InspectorAuthor 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set class
     *
     * @param \espend\Inspector\CoreBundle\Entity\InspectorClass $class
     * @return InspectorAuthorClass
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
