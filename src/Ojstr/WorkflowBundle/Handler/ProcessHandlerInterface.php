<?php

namespace Ojstr\WorkflowBundle\Handler;

use Ojstr\WorkflowBundle\Entity\ModelState;
use Ojstr\WorkflowBundle\Model\ModelInterface;

/**
 * Process handler interface.
 *
 * @author Cédric Girard <c.girard@lexik.fr>
 */
interface ProcessHandlerInterface {

    /**
     * Start the current process for the given model.
     *
     * @param  ModelInterface $model
     * @return ModelState
     */
    public function start(ModelInterface $model);

    /**
     * Tries to reach a step with the given model.
     *
     * @param  ModelInterface $model
     * @param  string         $stateName
     * @return ModelState
     */
    public function reachNextState(ModelInterface $model, $stateName);

    /**
     * Returns the current model state.
     *
     * @param  ModelInterface $model
     * @return ModelState
     */
    public function getCurrentState(ModelInterface $model);

    /**
     * Returns all model state of the given model object.
     *
     * @param  ModelInterface $model
     * @param  boolean        $successOnly
     * @return array
     */
    public function getAllStates(ModelInterface $model, $successOnly = true);

    /**
     * Returns true if the given model has completed the process.
     *
     * @param  ModelInterface $model
     * @return boolean
     */
    public function isProcessComplete(ModelInterface $model);
}
