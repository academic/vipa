<?php

namespace Vipa\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Vipa\CoreBundle\Entity\DisplayTrait;
use Vipa\CoreBundle\Annotation\Display as Display;

/**
 * JournalSubmissionFile
 * @GRID\Source(columns="id,title,locale,visible")
 */
class JournalSubmissionFile implements JournalItemInterface
{
    use DisplayTrait;

    /** @var  Journal */
    private $journal;

    /**
     * @var integer
     * @GRID\Column(title="ID")
     */
    private $id;

    /**
     * @var string
     * @GRID\Column(title="submission_file.title",safe = false)
     */
    private $title;

    /**
     * @var string
     */
    private $detail;

    /**
     * @var boolean
     * @GRID\Column(title="submission_file.visible")
     */
    private $visible;

    /**
     * @var boolean
     * @GRID\Column(title="submission_file.required")
     */
    private $required;

    /**
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * @var string
     * @GRID\Column(title="Locale")
     */
    private $locale;

    /**
     * @var string
     * @GRID\Column(title="file")
     * @Display\File(path="submissionfiles")
     */
    private $file;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * set id
     *
     * @param null $id
     * @return $this
     */
    public function setId($id = null)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return JournalSubmissionFile
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set detail
     *
     * @param  string $detail
     * @return JournalSubmissionFile
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set visible
     *
     * @param  boolean $visible
     * @return JournalSubmissionFile
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get required
     *
     * @return boolean
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Set required
     *
     * @param  boolean $required
     * @return JournalSubmissionFile
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set deletedAt
     *
     * @param  \DateTime $deletedAt
     * @return JournalSubmissionFile
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get journal
     *
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set journal
     *
     * @param  Journal $journal
     * @return JournalSubmissionFile
     */
    public function setJournal(Journal $journal = null)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set locale
     *
     * @param  string $locale
     * @return JournalSubmissionFile
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
