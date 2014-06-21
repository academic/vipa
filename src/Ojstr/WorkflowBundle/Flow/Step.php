<?php

namespace Ojstr\WorkflowBundle\Flow;

/**
 * Step of a process.
 *
 * @author Cédric Girard <c.girard@lexik.fr>
 */
class Step extends Node {

    /**
     * @var string
     */
    protected $label;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var array
     */
    protected $modelStatus;

    /**
     * @var string
     */
    protected $onInvalid;

    /**
     * Construct.
     *
     * @param string $name
     * @param string $label
     * @param array  $nextStates
     * @param array  $modelStatus
     * @param array  $roles
     * @param string $onInvalid
     */
    public function __construct($name, $label, array $nextStates = array(), array $modelStatus = array(), array $roles = array(), $onInvalid = null) {
        parent::__construct($name, $nextStates);

        $this->label = $label;
        $this->modelStatus = $modelStatus;
        $this->roles = $roles;
        $this->onInvalid = $onInvalid;
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->getLabel();
    }

    /**
     * Get step label.
     *
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * Returns the status updates one the step is reached.
     *
     * @return array
     */
    public function getModelStatus() {
        return $this->modelStatus;
    }

    /**
     * Returns true if the step has a status to update once it reached.
     *
     * @return boolean
     */
    public function hasModelStatus() {
        return !empty($this->modelStatus);
    }

    /**
     * Returns required roles to reach the step.
     *
     * @return array
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * Returns the step to reach if validation fail to reach this step.
     *
     * @return string
     */
    public function getOnInvalid() {
        return $this->onInvalid;
    }

}
