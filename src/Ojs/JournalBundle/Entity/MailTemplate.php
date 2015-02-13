<?php

namespace Ojs\JournalBundle\Entity;

/**
 * MailTemplate
 */
class MailTemplate extends \Ojs\Common\Entity\GenericExtendedEntity
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $journalId;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $lang;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $template;

    /**
     *
     * @var \Ojs\JournalBundle\Entity\Journal
     */
    private $journal;

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
     * Set journalId
     *
     * @param integer $journalId
     * @return MailTemplate
     */
    public function setJournalId($journalId)
    {
        $this->journalId = $journalId;

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
     * Set type
     *
     * @param string $type
     * @return MailTemplate
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return MailTemplate
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set lang
     *
     * @param string $lang
     * @return MailTemplate
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * Get lang
     *
     * @return strşng 
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set template
     *
     * @param string $template
     * @return MailTemplate
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string 
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     *
     * @param  \Ojs\JournalBundle\Entity\Journal        $journal
     * @return \Ojs\JournalBundle\Entity\MailTemplate
     */
    public function setJournal(\Ojs\JournalBundle\Entity\Journal $journal)
    {
        $this->journal = $journal;
        return $this;
    }

    /**
     *
     * @return \Ojs\JournalBundle\Entity\Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

}
