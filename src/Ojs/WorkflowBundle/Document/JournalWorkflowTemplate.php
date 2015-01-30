<?php

namespace Ojs\WorkflowBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 *
 * @MongoDb\Document(collection="journal_workflow_template")
 */
class JournalWorkflowTemplate
{

    /**
     * @MongoDb\Id
     */
    protected $id;

    /** @MongoDb\String */
    protected $title;

    /**
     *  @MongoDb\ReferenceOne(targetDocument="JournalWorkflowTemplateStep")
     */
    protected $firstNode;


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set firstNode
     *
     * @param Ojs\WorkflowBundle\Document\JournalWorkflowTemplateStep $firstNode
     * @return self
     */
    public function setFirstNode(\Ojs\WorkflowBundle\Document\JournalWorkflowTemplateStep $firstNode)
    {
        $this->firstNode = $firstNode;
        return $this;
    }

    /**
     * Get firstNode
     *
     * @return Ojs\WorkflowBundle\Document\JournalWorkflowTemplateStep $firstNode
     */
    public function getFirstNode()
    {
        return $this->firstNode;
    }
}
