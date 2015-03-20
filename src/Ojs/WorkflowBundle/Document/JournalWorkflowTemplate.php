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

    /** @MongoDb\String */
    protected $description;

    /** @MongoDb\Boolean */
    protected $isSystemTemplate;

    /** @MongoDb\Int */
    protected $journalId;

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
     * Set description
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isSystemTemplate
     *
     * @param boolean $isSystemTemplate
     * @return self
     */
    public function setIsSystemTemplate($isSystemTemplate)
    {
        $this->isSystemTemplate = $isSystemTemplate;
        return $this;
    }

    /**
     * Get isSystemTemplate
     *
     * @return string $isSystemTemplate
     */
    public function getIsSystemTemplate()
    {
        return $this->isSystemTemplate;
    }

    /**
     * Set journalId
     *
     * @param int $journalId
     * @return self
     */
    public function setJournalId($journalId)
    {
        $this->journalId = $journalId;
        return $this;
    }

    /**
     * Get journalId
     *
     * @return int $journalId
     */
    public function getJournalId()
    {
        return $this->journalId;
    }

}
