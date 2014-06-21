<?php

namespace Ojstr\WorkflowBundle\Twig\Extension;

use Ojstr\WorkflowBundle\Entity\ModelState;
use Ojstr\WorkflowBundle\Handler\ProcessAggregator;
use Ojstr\WorkflowBundle\Flow\Step;

class WorkflowExtension extends \Twig_Extension {

    /**
     * @var ProcessAggregator
     */
    private $aggregator;

    /**
     * Construct.
     *
     * @param ProcessAggregator $aggregator
     */
    public function __construct(ProcessAggregator $aggregator) {
        $this->aggregator = $aggregator;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            'get_step_label' => new \Twig_Function_Method($this, 'getStepLabel'),
            'get_state_messsage' => new \Twig_Function_Method($this, 'getStateMessage'),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'workflow_extension';
    }

    /**
     * Return the state's step label.
     *
     * @param  ModelState $state
     * @return string
     */
    public function getStepLabel(ModelState $state) {
        $step = $this->aggregator
                ->getProcess($state->getProcessName())
                ->getStep($state->getStepName());

        return $step instanceof Step ? $step->getLabel() : '';
    }

    /**
     * Returns the state message.
     *
     * @param  ModelState $state
     * @return string
     */
    public function getStateMessage(ModelState $state) {
        $message = '';

        if ($state->getSuccessful()) {
            $data = $state->getData();

            $message = isset($data['success_message']) ? $data['success_message'] : '';
        } else {
            $message = implode("\n", $state->getErrors());
        }

        return $message;
    }

}
