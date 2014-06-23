<?php

namespace Ojstr\WorkflowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WorkflowStep
 */
class WorkflowStep {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $journalId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var integer
     */
    private $nextId;

    /**
     * @var integer
     */
    private $prevId;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $roles;

    public function __construct() {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set journalId
     *
     * @param integer $journalId
     * @return WorkflowStep
     */
    public function setJournalId($journalId) {
        $this->journalId = $journalId;

        return $this;
    }

    /**
     * Get journalId
     *
     * @return integer 
     */
    public function getJournalId() {
        return $this->journalId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return WorkflowStep
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return WorkflowStep
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set nextId
     *
     * @param integer $nextId
     * @return WorkflowStep
     */
    public function setNextId($nextId) {
        $this->nextId = $nextId;

        return $this;
    }

    /**
     * Get nextId
     *
     * @return integer 
     */
    public function getNextId() {
        return $this->nextId;
    }

    /**
     * Set prevId
     *
     * @param integer $prevId
     * @return WorkflowStep
     */
    public function setPrevId($prevId) {
        $this->prevId = $prevId;

        return $this;
    }

    /**
     * Get prevId
     *
     * @return integer 
     */
    public function getPrevId() {
        return $this->prevId;
    }

    /**
     * 
     * @param \Ojstr\WorkflowBundle\Entity\WorkflowStepRole $role
     * @return \Ojstr\WorkflowBundle\Entity\WorkflowStep
     */
    public function addRole(\Ojstr\WorkflowBundle\Entity\WorkflowStepRole $role) {
        $this->roles[] = $roles;

        return $this;
    }

    public function removeRole(\Ojstr\WorkflowBundle\Entity\WorkflowStepRole $role) {
        $this->roles->removeElement($role);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles() {
        return $this->articles;
    }

}
