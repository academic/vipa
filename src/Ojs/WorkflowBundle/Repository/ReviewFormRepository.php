<?php

namespace Ojs\WorkflowBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 *
 * @MongoDb\Document(collection="review_forms")
 */
class ReviewFormRepository extends DocumentRepository
{

    /**
     * @param integer $journalId
     * @return \Ojs\WorkflowBundle\Document\ReviewForm
     */
    public function getJournalForms($journalId)
    {
        return $this->createQueryBuilder()
                        ->field('journalId')->equals($journalId)
                        ->getQuery()
                        ->execute();
    }

}
