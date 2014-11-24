<?php

namespace Ojs\JournalBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * This collection holds resumable article submission data
 * @MongoDb\Document(collection="article_submission_progress")
 */
class ArticleSubmissionProgress
{

    /**
     * @MongoDb\Id
     */
    protected $id;

    /**
     * @MongoDb\Int
     */
    protected $current_step;

    /**
     * @MongoDB\Date
     */
    protected $started_date;

    /**
     * @MongoDB\Date
     */
    protected $last_resume_date;

    /** @MongoDb\Int @MongoDb\Index() */
    protected $userId;

    /**
     * @MongoDb\Int
     */
    protected $journal_id;

    /**
     * @MongoDb\String
     */
    protected $primary_language;

    /** @MongoDb\Int @MongoDb\Index() */
    protected $article_id;

    /** @MongoDb\Collection */
    protected $languages;

    /**
     * article data with locale key  array("en"=> [an array of article data for locale "en"], "tr"=> [...])
     * @MongoDB\Hash
     */
    protected $article_data;

    /**
     * authors
     * @MongoDB\Hash
     */
    protected $authors;

    /**
     * @MongoDB\Hash
     */
    protected $citations;

    /**
     * @MongoDB\Hash
     */
    protected $files;

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
     * Set currentStep
     *
     * @param int $currentStep
     * @return self
     */
    public function setCurrentStep($currentStep)
    {
        $this->current_step = $currentStep;
        return $this;
    }

    /**
     * Get currentStep
     *
     * @return int $currentStep
     */
    public function getCurrentStep()
    {
        return $this->current_step;
    }

    /**
     * Set startedDate
     *
     * @param date $startedDate
     * @return self
     */
    public function setStartedDate($startedDate)
    {
        $this->started_date = $startedDate;
        return $this;
    }

    /**
     * Get startedDate
     *
     * @return date $startedDate
     */
    public function getStartedDate()
    {
        return $this->started_date;
    }

    /**
     * Set lastResumeDate
     *
     * @param date $lastResumeDate
     * @return self
     */
    public function setLastResumeDate($lastResumeDate)
    {
        $this->last_resume_date = $lastResumeDate;
        return $this;
    }

    /**
     * Get lastResumeDate
     *
     * @return date $lastResumeDate
     */
    public function getLastResumeDate()
    {
        return $this->last_resume_date;
    }

    /**
     * Set userId
     *
     * @param int $userId
     * @return self
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Get userId
     *
     * @return int $userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set articleId
     *
     * @param int $articleId
     * @return self
     */
    public function setArticleId($articleId)
    {
        $this->article_id = $articleId;
        return $this;
    }

    /**
     * Get articleId
     *
     * @return int $articleId
     */
    public function getArticleId()
    {
        return $this->article_id;
    }

    /**
     * Set languages
     *
     * @param collection $languages
     * @return self
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
        return $this;
    }

    /**
     * Get languages
     *
     * @return collection $languages
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Set articleData
     *
     * @param hash $articleData
     * @return self
     */
    public function setArticleData($articleData)
    {
        $this->article_data = $articleData;
        return $this;
    }

    /**
     * Get articleData
     *
     * @return hash $articleData
     */
    public function getArticleData()
    {
        return $this->article_data;
    }

    /**
     * Set authors
     *
     * @param hash $authors
     * @return self
     */
    public function setAuthors($authors)
    {
        $this->authors = $authors;
        return $this;
    }

    /**
     * Get authors
     *
     * @return hash $authors
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * Set citations
     *
     * @param hash $citations
     * @return self
     */
    public function setCitations($citations)
    {
        $this->citations = $citations;
        return $this;
    }

    /**
     * Get citations
     *
     * @return hash $citations
     */
    public function getCitations()
    {
        return $this->citations;
    }

    /**
     * Set files
     *
     * @param hash $files
     * @return self
     */
    public function setFiles($files)
    {
        $this->files = $files;
        return $this;
    }

    /**
     * Get files
     *
     * @return hash $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set journalId
     *
     * @param int $journalId
     * @return self
     */
    public function setJournalId($journalId)
    {
        $this->journal_id = $journalId;
        return $this;
    }

    /**
     * Get journalId
     *
     * @return int $journalId
     */
    public function getJournalId()
    {
        return $this->journal_id;
    }

    /**
     * Set primaryLanguage
     *
     * @param string $primaryLanguage
     * @return self
     */
    public function setPrimaryLanguage($primaryLanguage)
    {
        $this->primary_language = $primaryLanguage;
        return $this;
    }

    /**
     * Get primaryLanguage
     *
     * @return string $primaryLanguage
     */
    public function getPrimaryLanguage()
    {
        return $this->primary_language;
    }

}
