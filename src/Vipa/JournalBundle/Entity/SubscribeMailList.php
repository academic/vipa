<?php

namespace Vipa\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Gedmo\Translatable\Translatable;
use Vipa\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * SubscribeMailList
 */
class SubscribeMailList implements Translatable, JournalItemInterface
{
    use GenericEntityTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     *
     * @var Journal
     */
    private $journal;

    /**
     * @var string
     */
    private $mail;

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
     * @param  Journal $journal
     * @return Issue
     */
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set mail
     *
     * @param  string $mail
     * @return $this
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return SubscribeMailList
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
     * @return SubscribeMailList
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }
}
