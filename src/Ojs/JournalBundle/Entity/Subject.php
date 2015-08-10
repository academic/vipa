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
use Ojs\JournalBundle\Entity\SubjectTranslation;

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

    protected $translations;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->journals = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(SubjectTranslation $t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function setTranslations($translations)
    {
        foreach($translations as $translation){
            $this->addTranslation($translation);
        }
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

    /**
     * Set lft
     *
     * @param integer $lft
     *
     * @return Subject
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     *
     * @return Subject
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     *
     * @return Subject
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     *
     * @return Subject
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Subject
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
     * @return Subject
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Add child
     *
     * @param \Ojs\JournalBundle\Entity\Subject $child
     *
     * @return Subject
     */
    public function addChild(\Ojs\JournalBundle\Entity\Subject $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Ojs\JournalBundle\Entity\Subject $child
     */
    public function removeChild(\Ojs\JournalBundle\Entity\Subject $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Remove translation
     *
     * @param \Ojs\JournalBundle\Entity\SubjectTranslation $translation
     */
    public function removeTranslation(\Ojs\JournalBundle\Entity\SubjectTranslation $translation)
    {
        $this->translations->removeElement($translation);
    }
}
