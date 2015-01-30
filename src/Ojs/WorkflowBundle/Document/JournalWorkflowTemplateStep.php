<?php

namespace Ojs\WorkflowBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 *
 * @MongoDb\Document(collection="journal_workflow_template_step")
 */
class JournalWorkflowTemplateStep extends JournalWorkflowStep
{

    public function __construct()
    {
        $this->setCanEdit(true);
        $this->setIsVisible(true);
        $this->setMaxdays(15);
        $this->setJournalid(null);
    }

}
