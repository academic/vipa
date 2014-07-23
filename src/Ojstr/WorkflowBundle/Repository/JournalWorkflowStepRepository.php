<?php

namespace Ojstr\WorkflowBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * 
 * @MongoDb\Document(collection="journal_workflow_steps") 
 */
class JournalWorkflowStepRepository extends DocumentRepository {

    /**
     * 
     * $firstStep = $this->get('doctrine_mongodb')
     *  ->getManager()
     *  ->getRepository('OjstrWorkflowBundle:JournalWorkflowStep')
     *  ->findFirstStep();
     * 
     * @return \Ojstr\WorkflowBundle\Document\JournalWorkflowStep
     */
    public function findFirstStep() {
        return $this->createQueryBuilder()
                        ->field('firststep')->equals(TRUE)
                        ->getQuery()
                        ->getSingleResult();
    }

    /**
     * 
     * @return \Ojstr\WorkflowBundle\Document\JournalWorkflowStep
     */
    public function findLastStepStep() {
        return $this->createQueryBuilder()
                        ->field('lastStep')->equals(TRUE)
                        ->getQuery()
                        ->getSingleResult();
    }

    public function findAllOrderedByTitle() {
        return $this->createQueryBuilder()
                        ->sort('title', 'ASC')
                        ->getQuery()
                        ->execute();
    }

}
