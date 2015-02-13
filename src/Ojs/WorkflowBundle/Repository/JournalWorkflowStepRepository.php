<?php

namespace Ojs\WorkflowBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 *
 * @MongoDb\Document(collection="journal_workflow_steps")
 */
class JournalWorkflowStepRepository extends DocumentRepository
{
    /**
     *
     * $firstStep = $this->get('doctrine_mongodb')
     *  ->getManager()
     *  ->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
     *  ->findFirstStep();
     *
     * @return \Ojs\WorkflowBundle\Document\JournalWorkflowStep
     */
    public function findFirstStep()
    {
        return $this->createQueryBuilder()
                        ->field('firststep')->equals(true)
                        ->getQuery()
                        ->getSingleResult();
    }

    /**
     *
     * @return \Ojs\WorkflowBundle\Document\JournalWorkflowStep
     */
    public function findLastStepStep()
    {
        return $this->createQueryBuilder()
                        ->field('lastStep')->equals(true)
                        ->getQuery()
                        ->getSingleResult();
    }

    public function findAllOrderedByTitle()
    {
        return $this->createQueryBuilder()
                        ->sort('title', 'ASC')
                        ->getQuery()
                        ->execute();
    }

}
