<?php

namespace Ojs\WorkflowBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 *
 * @MongoDb\Document(collection="journal_workflow_template_step")
 */
class JournalWorkflowTemplateStep extends JournalWorkflowStep {

    public function __construct() {
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
     *  @MongoDb\ReferenceMany(targetDocument="JournalWorkflowTemplateStep",nullable=true)
     */
    private $nextSteps;

    /**
     * Add nextStep
     *
     * @param Ojs\WorkflowBundle\Document\JournalWorkflowStep $nextStep
     */
    public function addNextStep(\Ojs\WorkflowBundle\Document\JournalWorkflowStep $nextStep) {
        $this->nextSteps[] = $nextStep;
    }

    /**
     * Remove nextStep
     *
     * @param Ojs\WorkflowBundle\Document\JournalWorkflowStep $nextStep
     */
    public function removeNextStep(\Ojs\WorkflowBundle\Document\JournalWorkflowStep $nextStep) {
        $this->nextSteps->removeElement($nextStep);
    }

    /**
     * Get nextSteps
     *
     * @return Doctrine\Common\Collections\Collection $nextSteps
     */
    public function getNextSteps() {
        return $this->nextSteps;
    }

    /**
     * Set template
     *
     * @param Ojs\WorkflowBundle\Document\JournalWorkflowTemplate $template
     * @return self
     */
    public function setTemplate(\Ojs\WorkflowBundle\Document\JournalWorkflowTemplate $template) {
        $this->template = $template;
        return $this;
    }

    /**
     * Get template
     *
     * @return Ojs\WorkflowBundle\Document\JournalWorkflowTemplate $template
     */
    public function getTemplate() {
        return $this->template;
    }

}
