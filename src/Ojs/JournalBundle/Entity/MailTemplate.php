<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Gedmo\Translatable\Translatable;
use GoDisco\AclTreeBundle\Annotation\AclParent;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * MailTemplate
 * @GRID\Source(columns="id,journal.title,type,languages.code, subject")
 */
class MailTemplate implements Translatable
{
    use GenericEntityTrait;

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
     * @var Journal
     * @GRID\Column(title="mailtemplate.journal", field="journal.title")
     * @AclParent
     */
    private $journal;

    /**
     * @var ArrayCollection|Lang[]
     * @GRID\Column(title="mailtemplate.languages",field="languages.code",type="array")
     */
    protected $languages;

    public function __construct()
    {
        $this->languages = new ArrayCollection();
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
     * Set journalId
     *
     * @param  integer      $journalId
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
     * @param  string       $type
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
     * @param  string       $subject
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
     * @param  string       $lang
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
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
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
     * @param  Journal      $journal
     * @return MailTemplate
     */
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;

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
     * Add languages
     *
     * @param  Lang         $languages
     * @return MailTemplate
     */
    public function addLanguage(Lang $languages)
    {
        $this->languages[] = $languages;

        return $this;
    }

    /**
     * Remove languages
     *
     * @param Lang $languages
     */
    public function removeLanguage(Lang $languages)
    {
        $this->languages->removeElement($languages);
    }

    /**
     * Get languages
     *
     * @return Collection
     */
    public function getLanguages()
    {
        return $this->languages;
    }
}
