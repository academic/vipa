<?php

namespace Ojstr\WorkflowBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Used to store a state of a model object.
 *
 */
class ModelState {

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $workflowIdentifier;

    /**
     * @var string
     */
    protected $processName;

    /**
     * @var string
     */
    protected $stepName;

    /**
     * @var boolean
     */
    protected $successful;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $errors;

    /**
     * @var ModelState
     */
    protected $previous;

    /**
     * @var ArrayCollection
     */
    protected $next;

    /**
     * Construct.
     */
    public function __construct() {
        $this->createdAt = new \DateTime('now');
        $this->next = new ArrayCollection();
    }

    /**
     * Get Id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get workflowIdentifier
     *
     * @return string
     */
    public function getWorkflowIdentifier() {
        return $this->workflowIdentifier;
    }

    /**
     * Set workflowIdentifier
     *
     * @param string $workflowIdentifier
     */
    public function setWorkflowIdentifier($workflowIdentifier) {
        $this->workflowIdentifier = $workflowIdentifier;
    }

    /**
     * Get processName
     *
     * @return string
     */
    public function getProcessName() {
        return $this->processName;
    }

    /**
     * Set processName
     *
     * @param string $processName
     */
    public function setProcessName($processName) {
        $this->processName = $processName;
    }

    /**
     * Get stepName
     *
     * @return string
     */
    public function getStepName() {
        return $this->stepName;
    }

    /**
     * Set stepName
     *
     * @param string $stepName
     */
    public function setStepName($stepName) {
        $this->stepName = $stepName;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData() {
        return json_decode($this->data, true);
    }

    /**
     * Set data
     *
     * @param mixed $data An array or a JSON string
     */
    public function setData($data) {
        if (!is_string($data)) {
            $data = json_encode($data);
        }

        $this->data = $data;
    }

    /**
     * Get successful
     *
     * @return boolean
     */
    public function getSuccessful() {
        return $this->successful;
    }

    /**
     * Set successful
     *
     * @param boolean
     */
    public function setSuccessful($successful) {
        $this->successful = (boolean) $successful;
    }

    /**
     * Get errors
     *
     * @return string
     */
    public function getErrors() {
        return json_decode($this->errors, true);
    }

    /**
     * Set errors
     *
     * @param string $errors
     */
    public function setErrors($errors) {
        if (!is_string($errors)) {
            $errors = json_encode($errors);
        }

        $this->errors = $errors;
    }

    /**
     * Get previous
     *
     * @return \Ojstr\WorkflowBundle\Entity\ModelState
     */
    public function getPrevious() {
        return $this->previous;
    }

    /**
     * Set previous
     *
     * @param ModelState $state
     */
    public function setPrevious(ModelState $state) {
        $this->previous = $state;
    }

    /**
     * Get next
     *
     * @return ArrayCollection
     */
    public function getNext() {
        return $this->next;
    }

    /**
     * Add next
     *
     * @param ModelState $state
     */
    public function addNext(ModelState $state) {
        $state->setPrevious($this);

        $this->next[] = $state;
    }

}
