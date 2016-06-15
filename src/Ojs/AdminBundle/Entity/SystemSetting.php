<?php

namespace Ojs\AdminBundle\Entity;

/**
 * AdminSystemSetting
 */
class SystemSetting
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var bool
     */
    private $userRegistrationActive;

    /**
     * @var bool
     */
    private $publisherApplicationActive;

    /**
     * @var bool
     */
    private $journalApplicationActive;

    /**
     * @var bool
     */
    private $articleSubmissionActive;

    /**
     * @var string
     */
    private $systemFooterScript;

    public function __construct()
    {
    }

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
     * @return boolean
     */
    public function isUserRegistrationActive()
    {
        return $this->userRegistrationActive;
    }

    /**
     * @param boolean $userRegistrationActive
     *
     * @return $this
     */
    public function setUserRegistrationActive($userRegistrationActive)
    {
        $this->userRegistrationActive = $userRegistrationActive;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPublisherApplicationActive()
    {
        return $this->publisherApplicationActive;
    }

    /**
     * @param boolean $publisherApplicationActive
     *
     * @return $this
     */
    public function setPublisherApplicationActive($publisherApplicationActive)
    {
        $this->publisherApplicationActive = $publisherApplicationActive;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isArticleSubmissionActive()
    {
        return $this->articleSubmissionActive;
    }

    /**
     * @param boolean $articleSubmissionActive
     *
     * @return $this
     */
    public function setArticleSubmissionActive($articleSubmissionActive)
    {
        $this->articleSubmissionActive = $articleSubmissionActive;

        return $this;
    }

    /**
     * @return string
     */
    public function getSystemFooterScript()
    {
        return $this->systemFooterScript;
    }

    /**
     * @param string $systemFooterScript
     *
     * @return $this
     */
    public function setSystemFooterScript($systemFooterScript)
    {
        $this->systemFooterScript = $systemFooterScript;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isJournalApplicationActive()
    {
        return $this->journalApplicationActive;
    }

    /**
     * @param boolean $journalApplicationActive
     *
     * @return $this
     */
    public function setJournalApplicationActive($journalApplicationActive)
    {
        $this->journalApplicationActive = $journalApplicationActive;

        return $this;
    }
}

