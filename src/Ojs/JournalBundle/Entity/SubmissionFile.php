<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * SubmissionFile
 * @GRID\Source(columns="id,label,locale,visible")
 */
class SubmissionFile
{
    /**
     * @var integer
     * @GRID\Column(title="ID")
     */
    private $id;

    /**
     * @var string
     * @GRID\Column(title="submission_file.label",safe = false)
     */
    private $label;

    /**
     * @var string
     */
    private $detail;

    /**
     * @var integer
     */
    private $journalId;

    /**
     * @var integer
     */
    private $articleId;

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
     * @var Journal
     */
    private $journal;

    /**
     * @var Journal
     */
    private $article;

    /**
     * @var string
     * @GRID\Column(title="Locale")
     */
    private $locale;

    /**
     * @var string
     * @GRID\Column(title="file")
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
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set label
     *
     * @param  string              $label
     * @return SubmissionFile
     */
    public function setLabel($label)
    {
        $this->label = $label;

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
     * @param  string              $detail
     * @return SubmissionFile
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get journalId
     *
     * @return integer
     */
    public function getJournalId()
    {
        return $this->journalId;
    }

    /**
     * Set journalId
     *
     * @param  integer             $journalId
     * @return SubmissionFile
     */
    public function setJournalId($journalId)
    {
        $this->journalId = $journalId;

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
     * @param  boolean             $visible
     * @return SubmissionFile
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
     * @param  boolean             $required
     * @return SubmissionFile
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
     * @param  \DateTime           $deletedAt
     * @return SubmissionFile
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
     * @param  Journal             $journal
     * @return SubmissionFile
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
     * @param  string              $locale
     * @return SubmissionFile
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
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return int
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @param int $articleId
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     */
    public function setArticle(Article $article)
    {
        $this->article = $article;
    }
}
