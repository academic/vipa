<?php

namespace Ojs\JournalBundle\Entity;

use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * Class SubmissionSetting
 * @package Ojs\JournalBundle\Entity
 */
class SubmissionSetting
{
    use GenericEntityTrait;


    protected $id;

    /**
     * @var bool
     */
    private $submissionEnabled;

    /**
     * @var Journal
     */
    private $journal;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function getSubmissionEnabled()
    {
        return $this->submissionEnabled;
    }

    /**
     * @param bool $submissionEnabled
     *
     * @return $this
     */
    public function setSubmissionEnabled($submissionEnabled)
    {
        $this->submissionEnabled = $submissionEnabled;

        return $this;
    }

    /**
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * @param Journal $journal
     *
     * @return $this
     */
    public function setJournal($journal)
    {
        $this->journal = $journal;

        return $this;
    }
}
