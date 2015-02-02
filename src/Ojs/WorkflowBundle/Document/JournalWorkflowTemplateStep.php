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

    /**
     * @MongoDb\ReferenceOne(targetDocument="JournalWorkflowTemplate")
     */
    protected $template;

    /**
     * Set template
     *
     * @param Ojs\WorkflowBundle\Document\JournalWorkflowTemplate $template
     * @return self
     */
    public function setTemplate(\Ojs\WorkflowBundle\Document\JournalWorkflowTemplate $template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Get template
     *
     * @return Ojs\WorkflowBundle\Document\JournalWorkflowTemplate $template
     */
    public function getTemplate()
    {
        return $this->template;
    }

}
