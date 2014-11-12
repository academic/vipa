<?php

namespace Ojs\JournalBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Subject
 * @ExclusionPolicy("all")
 */
class Subject extends \Ojs\Common\Entity\GenericExtendedEntity
{

    /**
     * @var integer
     * @Expose
     */
    private $id;

    /**
     * @var string
     * @Expose
     */
    private $subject;

    /**
     * @var string
     * @Expose
     */
    private $description;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $journals;

    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->journas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set subject
     *
     * @param  string  $subject
     * @return Subject
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
     * Set description
     *
     * @param  string  $description
     * @return Subject
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add users
     *
     * @param  \Ojs\UserBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\Ojs\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;
        return $this;
    }

    /**
     * Remove users
     *
     * @param \Ojs\UserBundle\Entity\User $users
     */
    public function removeUser(\Ojs\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Get subjects
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJournals()
    {
        return $this->journals;
    }

    /**
     * Add subject
     *
     * @param  \Ojs\JournalBundle\Entity\Journal $journal
     * @return Role
     */
    public function addJournal(\Ojs\UserBundle\Entity\User $journal)
    {
        $this->journals[] = $journal;
        return $this;
    }

    /**
     * Remove journal
     *
     * @param \Ojs\UserBundle\Entity\User $journal
     */
    public function removeJournal(\Ojs\JournalBundle\Entity\Journal $journal)
    {
        $this->journals->removeElement($journal);
    }

    public function hasJournals()
    {
        $totalJournals = $this->journals->count();
        return $totalJournals > 0;
    }

}
