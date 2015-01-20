<?php

namespace Ojs\WorkflowBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 *
 * @MongoDb\Document(collection="journal_workflow_step_template",repositoryClass="Ojs\WorkflowBundle\Repository\JournalWorkflowStepTemplateRepository")
 */
class JournalWorkflowStepTemplate extends JournalWorkflowStep
{
    public function __construct()
    {
        $this->setCanEdit(true);
        $this->setIsVisible(true);
        $this->setMaxdays(15);
    }
    
}
