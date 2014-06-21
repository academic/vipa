<?php

namespace Ojstr\WorkflowBundle\Model;

use Ojstr\WorkflowBundle\Entity\ModelState;
use Ojstr\WorkflowBundle\Validation\ViolationList;
use Doctrine\ORM\EntityManager;

class ModelStorage {

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $om;

    /**
     * @var Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * Construct.
     *
     * @param EntityManager $om
     * @param string        $entityClass
     */
    public function __construct(EntityManager $om, $entityClass) {
        $this->om = $om;
        $this->repository = $this->om->getRepository($entityClass);
    }

    /**
     * Returns the current model state.
     *
     * @param ModelInterface $model
     * @param string         $processName
     * @param string         $stepName
     *
     * @return Ojstr\WorkflowBundle\Entity\ModelState
     */
    public function findCurrentModelState(ModelInterface $model, $processName, $stepName = null) {
        return $this->repository->findLatestModelState(
                        $model->getWorkflowIdentifier(), $processName, $stepName
        );
    }

    /**
     * Returns all model states.
     *
     * @param  ModelInterface $model
     * @param  string         $processName
     * @param  string         $successOnly
     * @return array
     */
    public function findAllModelStates(ModelInterface $model, $processName, $successOnly = true) {
        return $this->repository->findModelStates(
                        $model->getWorkflowIdentifier(), $processName, $successOnly
        );
    }

    /**
     * Create a new invalid model state.
     *
     * @param ModelInterface  $model
     * @param string          $processName
     * @param string          $stepName
     * @param ViolationList   $violationList
     * @param null|ModelState $previous
     *
     * @return ModelState
     */
    public function newModelStateError(ModelInterface $model, $processName, $stepName, ViolationList $violationList, $previous = null) {
        $modelState = $this->createModelState($model, $processName, $stepName, $previous);
        $modelState->setSuccessful(false);
        $modelState->setErrors($violationList->toArray());

        $this->om->persist($modelState);
        $this->om->flush($modelState);

        return $modelState;
    }

    /**
     * Delete all model states.
     *
     * @param ModelInterface $model
     * @param string         $processName
     */
    public function deleteAllModelStates(ModelInterface $model, $processName = null) {
        return $this->repository->deleteModelStates(
                        $model->getWorkflowIdentifier(), $processName
        );
    }

    /**
     * Create a new successful model state.
     *
     * @param  ModelInterface                                 $model
     * @param  string                                         $processName
     * @param  string                                         $stepName
     * @param  ModelState                                     $previous
     * @return \Ojstr\WorkflowBundle\Entity\ModelState
     */
    public function newModelStateSuccess(ModelInterface $model, $processName, $stepName, $previous = null) {
        $modelState = $this->createModelState($model, $processName, $stepName, $previous);
        $modelState->setSuccessful(true);

        $this->om->persist($modelState);
        $this->om->flush($modelState);

        return $modelState;
    }

    /**
     * Create a new model state.
     *
     * @param  ModelInterface                                 $model
     * @param  string                                         $processName
     * @param  string                                         $stepName
     * @param  ModelState                                     $previous
     * @return \Ojstr\WorkflowBundle\Entity\ModelState
     */
    protected function createModelState(ModelInterface $model, $processName, $stepName, $previous = null) {
        $modelState = new ModelState();
        $modelState->setWorkflowIdentifier($model->getWorkflowIdentifier());
        $modelState->setProcessName($processName);
        $modelState->setStepName($stepName);
        $modelState->setData($model->getWorkflowData());

        if ($previous instanceof ModelState) {
            $modelState->setPrevious($previous);
        }

        return $modelState;
    }

}
