<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * MailTemplate
 * @GRID\Source(columns="id,description,lang,active,journalDefault")
 * @GRID\Source(columns="id,description,lang,active,useJournalDefault", groups={"journal"})
 */
class MailTemplate implements JournalItemInterface
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="ID")
     */
    private $id;

    /**
     * @var string
     * @GRID\Column(title="mailtemplate.type")
     */
    private $type;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     * @GRID\Column(title="mailtemplate.subject")
     */
    private $subject;

    /**
     * @var string
     */
    private $lang;

    /**
     * @var string
     */
    private $template;

    /**
     * @var bool
     */
    private $active = 0;

    /**
     * @var bool
     */
    private $useJournalDefault = 0;

    /**
     * @var bool
     */
    private $journalDefault = 0;

    /**
     *
     * @var Journal
     * @GRID\Column(title="journal")
     */
    private $journal;

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
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param  string       $type
     * @return MailTemplate
     */
    public function setType($type)
    {
        $this->type = $type;

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
     * Set subject
     *
     * @param  string       $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set lang
     *
     * @param  string       $lang
     * @return MailTemplate
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

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
     * Set template
     *
     * @param  string       $template
     * @return MailTemplate
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     *
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     *
     * @param  Journal      $journal
     * @return MailTemplate
     */
    public function setJournal(Journal $journal = null)
    {
        $this->journal = $journal;

        return $this;
    }


    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return MailTemplate
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return MailTemplate
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     *
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isUseJournalDefault()
    {
        return $this->useJournalDefault;
    }

    /**
     * @param boolean $useJournalDefault
     *
     * @return $this
     */
    public function setUseJournalDefault($useJournalDefault)
    {
        $this->useJournalDefault = $useJournalDefault;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isJournalDefault()
    {
        return $this->journalDefault;
    }

    /**
     * @param boolean $journalDefault
     *
     * @return $this
     */
    public function setJournalDefault($journalDefault)
    {
        $this->journalDefault = $journalDefault;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function __toString()
    {
        return $this->getType();
    }
}
