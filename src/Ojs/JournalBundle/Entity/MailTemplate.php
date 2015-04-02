<?php

namespace Ojs\JournalBundle\Entity;
use APY\DataGridBundle\Grid\Mapping as GRID;
/**
 * MailTemplate
 * @GRID\Source(columns="id,journal.title,type,languages.code, subject")
 */
class MailTemplate extends \Ojs\Common\Entity\GenericExtendedEntity
{

    /**
     * @var integer
     * @GRID\Column(title="ID")
     */
    private $id;

    /**
     * @var integer
     */
    private $journalId;

    /**
     * @var string
     * @GRID\Column(title="mailtemplate.type")
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
     * @GRID\Column(title="mailtemplate.journal", field="journal.title")
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

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @GRID\Column(title="mailtemplate.languages",field="languages.code",type="array")
     */
    protected  $languages;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->languages = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add languages
     *
     * @param \Ojs\JournalBundle\Entity\Lang $languages
     * @return MailTemplate
     */
    public function addLanguage(\Ojs\JournalBundle\Entity\Lang $languages)
    {
        $this->languages[] = $languages;

        return $this;
    }

    /**
     * Remove languages
     *
     * @param \Ojs\JournalBundle\Entity\Lang $languages
     */
    public function removeLanguage(\Ojs\JournalBundle\Entity\Lang $languages)
    {
        $this->languages->removeElement($languages);
    }

    /**
     * Get languages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLanguages()
    {
        return $this->languages;
    }
}
