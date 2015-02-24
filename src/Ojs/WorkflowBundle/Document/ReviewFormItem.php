<?php

namespace Ojs\WorkflowBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 *
 * @MongoDb\Document(collection="review_form_items",repositoryClass="Ojs\WorkflowBundle\Repository\ReviewFormItemRepository")
 */
class ReviewFormItem
{

    /**
     * @MongoDb\Id
     */
    protected $id;

    /**
     * @MongoDb\ObjectId
     */
    protected $formId;

    /** @MongoDb\String */
    protected $title;

    /** @MongoDb\Boolean */
    protected $mandotary;

    /** @MongoDb\Boolean */
    protected $confidential;

    /**
     * @MongoDb\String 
     *  
     *  - textbox
     *  - textarea
     *  - checkbox
     *  - radiobutton
     *  - dropdown
     *  - scale_1_5
     */
    protected $inputType;

    /** @MongoDb\Hash */
    protected $fields;

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
     * Set journalid
     *
     * @param int $journalid
     * @return self
     */
    public function setJournalid($journalid)
    {
        $this->journalid = $journalid;
        return $this;
    }

    /**
     * Get journalid
     *
     * @return int $journalid
     */
    public function getJournalid()
    {
        return $this->journalid;
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
     * Set mandotary
     *
     * @param boolean $mandotary
     * @return self
     */
    public function setMandotary($mandotary)
    {
        $this->mandotary = $mandotary;
        return $this;
    }

    /**
     * Get mandotary
     *
     * @return boolean $mandotary
     */
    public function getMandotary()
    {
        return $this->mandotary;
    }

    /**
     * Set inputType
     *
     * @param string $inputType
     * @return self
     */
    public function setInputType($inputType)
    {
        $this->inputType = $inputType;
        return $this;
    }

    /**
     * Get inputType
     *
     * @return string $inputType
     */
    public function getInputType()
    {
        return $this->inputType;
    }

    /**
     * Set fields
     *
     * @param hash $fields
     * @return self
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Get fields
     *
     * @return hash $fields
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Get formId
     *
     * @return id $formId
     */
    public function getFormId()
    {
        return $this->formId;
    }

    /**
     * Set formId
     *
     * @param object_id $formId
     * @return self
     */
    public function setFormId($formId)
    {
        $this->formId = $formId;
        return $this;
    }


    /**
     * Set confidential
     *
     * @param boolean $confidential
     * @return self
     */
    public function setConfidential($confidential)
    {
        $this->confidential = $confidential;
        return $this;
    }

    /**
     * Get confidential
     *
     * @return boolean $confidential
     */
    public function getConfidential()
    {
        return $this->confidential;
    }
}
