<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Ojs\Common\Entity\GenericEntityTrait;
use Ojs\UserBundle\Entity\User;

/**
 * Subject
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,subject,description")
 */
class Subject implements Translatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @Expose
     * @GRID\Column(title="id")
     */
    private $id;
    private $lft;
    private $lvl;
    private $rgt;
    private $root;

    /**
     * @var Subject
     * @Expose
     * @GRID\Column(title="parent")
     */
    private $parent;
    private $children;

    /**
     * @var string
     * @Expose
     * @GRID\Column(title="subject")
     */
    private $subject;

    /**
     * @var string
     * @Expose
     * @GRID\Column(title="description")
     */
    private $description;

    /**
     * @var Collection
     */
    private $users;

    /**
     * This data will be pre-calculated with scheduled tasks
     * @var int
     */
    private $totalJournalCount;

    /**
     * @var Collection
     */
    private $journals;

    /**
     * @var string
     */
    private $slug;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->journas = new ArrayCollection();
    }

    public function setParent(Subject $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     *
     * @return Subject
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getRoot()
    {
        return $this->root;
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
     * Set totalJournalCount
     * @param  integer $totalJournalCount
     * @return Subject
     */
    public function setTotalJournalCount($totalJournalCount)
    {
        $this->totalJournalCount = $totalJournalCount;

        return $this;
    }

    /**
     * Get totalJournalCount
     * @return integer
     */
    public function getTotalJournalCount()
    {
        return $this->totalJournalCount;
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
     * @param  User  $users
     * @return $this
     */
    public function addUser(User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param User $users
     */
    public function removeUser(User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Get subjects
     * @return Collection|Journal[]
     */
    public function getJournals()
    {
        return $this->journals;
    }

    /**
     * Add subject
     *
     * @param  Journal $journal
     * @return Subject
     */
    public function addJournal(Journal $journal)
    {
        $this->journals[] = $journal;

        return $this;
    }

    /**
     * Remove journal
     *
     * @param Journal $journal
     */
    public function removeJournal(Journal $journal)
    {
        $this->journals->removeElement($journal);
    }

    /**
     * @return bool
     */
    public function hasJournals()
    {
        $totalJournals = $this->journals->count();

        return $totalJournals > 0;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param  mixed $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    public function __toString()
    {
        return $this->subject;
    }
}
