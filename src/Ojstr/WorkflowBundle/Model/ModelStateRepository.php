<?php

namespace Ojstr\WorkflowBundle\Model;

use Doctrine\ORM\EntityRepository;

class ModelStateRepository extends EntityRepository {

    /**
     * Returns the last ModelState for the given workflow identifier.
     *
     * @param string $workflowIdentifier
     * @param string $processName
     * @param string $stepName
     *
     * @return Ojstr\WorkflowBundle\Entity\ModelState
     */
    public function findLatestModelState($workflowIdentifier, $processName, $stepName = null) {
        $qb = $this->createQueryBuilder('ms');
        $qb
                ->andWhere('ms.workflowIdentifier = :workflow_identifier')
                ->andWhere('ms.processName = :process')
                ->andWhere('ms.successful = :success')
                ->orderBy('ms.id', 'DESC')
                ->setParameter('workflow_identifier', $workflowIdentifier)
                ->setParameter('process', $processName)
                ->setParameter('success', true);

        if (null !== $stepName) {
            $qb
                    ->andWhere('ms.stepName = :stepName')
                    ->setParameter('stepName', $stepName);
        }

        $results = $qb->getQuery()->getResult();

        return isset($results[0]) ? $results[0] : null;
    }

    /**
     * Returns all model states for the given workflow identifier.
     *
     * @param  string  $workflowIdentifier
     * @param  string  $processName
     * @param  boolean $successOnly
     * @return array
     */
    public function findModelStates($workflowIdentifier, $processName, $successOnly) {
        $qb = $this->createQueryBuilder('ms')
                ->andWhere('ms.workflowIdentifier = :workflow_identifier')
                ->andWhere('ms.processName = :process')
                ->orderBy('ms.createdAt', 'ASC')
                ->setParameter('workflow_identifier', $workflowIdentifier)
                ->setParameter('process', $processName)
        ;

        if ($successOnly) {
            $qb->andWhere('ms.successful = :success')
                    ->setParameter('success', true);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Delete all model states for the given workflowIndentifier (and process name if given).
     *
     * @param  string $workflowIdentifier
     * @param  string $processName
     * @return int
     */
    public function deleteModelStates($workflowIdentifier, $processName = null) {
        $qb = $this->_em->createQueryBuilder()
                ->delete($this->_entityName, 'ms')
                ->andWhere('ms.workflowIdentifier = :workflow_identifier')
                ->setParameter('workflow_identifier', $workflowIdentifier);

        if (null !== $processName) {
            $qb->andWhere('ms.processName = :process')
                    ->setParameter('process', $processName);
        }

        return $qb->getQuery()->getResult();
    }

}
