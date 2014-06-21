<?php

namespace Ojstr\WorkflowBundle\Flow;

use Ojstr\WorkflowBundle\Model\ModelInterface;
use Ojstr\WorkflowBundle\Exception\WorkflowException;

/**
 * Conditional next state.
 *
 * @author Cédric Girard <c.girard@lexik.fr>
 */
class NextStateOr implements NextStateInterface {

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $targets;

    /**
     * Construct.
     *
     * @param string $name
     * @param string $type
     * @param Node   $target
     */
    public function __construct($name, $type, array $targets) {
        $this->name = $name;
        $this->type = $type;
        $this->targets = $targets;
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
    public function getTarget(ModelInterface $model) {
        $target = null;
        $i = 0;

        while ($i < count($this->targets) && null === $target) {
            $data = $this->targets[$i];

            $isDefault = ( null === $data['condition_object'] && null === $data['condition_method'] );
            $callback = array($data['condition_object'], $data['condition_method']);

            if ($isDefault || true === call_user_func($callback, $model)) {
                $target = $data['target'];
            }

            $i++;
        }

        if (null === $target) {
            throw new WorkflowException(sprintf('Next state "%s": can\'t choose target step according to given OR conditions.', $this->name));
        }

        return $target;
    }

}
