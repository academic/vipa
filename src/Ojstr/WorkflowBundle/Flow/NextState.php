<?php

namespace Ojstr\WorkflowBundle\Flow;

use Ojstr\WorkflowBundle\Model\ModelInterface;

/**
 * A State represent one of the available next element (step) a given step.
 *
 * @author Cédric Girard <c.girard@lexik.fr>
 */
class NextState implements NextStateInterface {

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var Step
     */
    protected $target;

    /**
     * Construct.
     *
     * @param string $name
     * @param string $type
     * @param Node   $target
     */
    public function __construct($name, $type, Node $target) {
        $this->name = $name;
        $this->type = $type;
        $this->target = $target;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getType() {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getTarget(ModelInterface $model = null) {
        return $this->target;
    }

}
