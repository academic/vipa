<?php

namespace Ojstr\WorkflowBundle\Flow;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Process class.
 *
 * @author Cédric Girard <c.girard@lexik.fr>
 */
class Process extends Node {

    /**
     * @var ArrayCollection
     */
    protected $steps;

    /**
     * @var string
     */
    protected $startStep;

    /**
     * @var array
     */
    protected $endSteps;

    /**
     * Construct.
     *
     * @param string $name
     * @param array  $steps
     * @param string $startStep
     * @param array  $endSteps
     */
    public function __construct($name, array $steps, $startStep, array $endSteps) {
        parent::__construct($name);

        $this->steps = new ArrayCollection($steps);
        $this->startStep = $startStep;
        $this->endSteps = $endSteps;
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->getName();
    }

    /**
     * Get process steps.
     *
     * @return ArrayCollection
     */
    public function getSteps() {
        return $this->steps;
    }

    /**
     * Returns a step by its name.
     *
     * @param string $name
     *
     * @return Ojstr\WorkflowBundle\Flow\Step
     */
    public function getStep($name) {
        return $this->steps->get($name);
    }

    /**
     * Returns the first step.
     *
     * @return Ojstr\WorkflowBundle\Flow\Step
     */
    public function getStartStep() {
        return $this->startStep;
    }

    /**
     * Returns an array of step name.
     *
     * @return array
     */
    public function getEndSteps() {
        return $this->endSteps;
    }

}
