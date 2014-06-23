<?php

namespace Ojstr\WorkflowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WorkflowStepRole
 */
class WorkflowStepRole {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $stepId;

    /**
     * @var string
     */
    private $roleName;

    /**
     * @var string
     */
    private $description;

    /**
     *
     * @var \Ojstr\WorkflowBundle\Entity\WorkflowStep
     */
    protected $step;

    /**
     * 
     * @param \Ojstr\WorkflowBundle\Entity\WorkflowStep $step
     * @return \Ojstr\WorkflowBundle\Entity\WorkflowStepRole
     */
    public function setStep($step) {
        $this->step = $step;
        return $this;
    }

    /**
     * 
     * @return \Ojstr\WorkflowBundle\Entity\WorkflowStep
     */
    public function getStep() {
        return $this->step;
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
     * Set stepId
     *
     * @param integer $stepId
     * @return WorkflowStepRole
     */
    public function setStepId($stepId) {
        $this->stepId = $stepId;

        return $this;
    }

    /**
     * Get stepId
     *
     * @return integer 
     */
    public function getStepId() {
        return $this->stepId;
    }

    /**
     * Set roleName
     *
     * @param string $roleName
     * @return WorkflowStepRole
     */
    public function setRoleName($roleName) {
        $this->roleName = $roleName;

        return $this;
    }

    /**
     * Get roleName
     *
     * @return string 
     */
    public function getRoleName() {
        return $this->roleName;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return WorkflowStepRole
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

}
