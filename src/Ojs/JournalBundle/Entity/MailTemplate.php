<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Translatable\Translatable;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * MailTemplate
 * @GRID\Source(columns="id,journal,type,languages.code, subject")
 */
class MailTemplate implements Translatable
{
    use GenericEntityTrait;

    /**
     * @var ArrayCollection|Lang[]
     * @GRID\Column(title="mailtemplate.languages",field="languages.code",type="array")
     */
    protected $languages;
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
     * @GRID\Column(title="journal")
     */
    private $journal;

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
     * @return MailTemplate
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
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;

        return $this;
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
}
