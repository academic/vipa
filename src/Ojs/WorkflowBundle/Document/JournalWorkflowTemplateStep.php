<?php

namespace Ojs\WorkflowBundle\Document;

use Doctrine\Common\Collections\Collection;
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
        $this->setMaxDays(15);
        $this->setJournalid(null);
    }

    /**
     * @MongoDb\ReferenceOne(targetDocument="JournalWorkflowTemplate")
     */
    protected $template;

    /**
     * @MongoDb\ReferenceMany(targetDocument="JournalWorkflowTemplateStep",nullable=true)
     *
     * @var Collection
     */
    private $nextSteps;

    /**
     * Add nextStep
     *
     * @param JournalWorkflowStep $nextStep
     */
    public function addNextStep(JournalWorkflowStep $nextStep)
    {
        $this->nextSteps[] = $nextStep;
    }

    /**
     * Remove nextStep
     *
     * @param JournalWorkflowStep $nextStep
     */
    public function removeNextStep(JournalWorkflowStep $nextStep)
    {
        $this->nextSteps->removeElement($nextStep);
    }

    /**
     * Get nextSteps
     *
     * @return Collection $nextSteps
     */
    public function getNextSteps()
    {
        return $this->nextSteps;
    }

    /**
     * Set template
     *
     * @param  JournalWorkflowTemplate $template
     * @return self
     */
    public function setTemplate(JournalWorkflowTemplate $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return JournalWorkflowTemplate $template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set nextSteps
     *
     * @param $nextSteps
     * @return $this
     */
    public function setNextSteps($nextSteps)
    {
        $this->nextSteps = $nextSteps;

        return $this;
    }
}
