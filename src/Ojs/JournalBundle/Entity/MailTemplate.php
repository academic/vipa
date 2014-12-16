<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MailTemplate
 */
class MailTemplate
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
