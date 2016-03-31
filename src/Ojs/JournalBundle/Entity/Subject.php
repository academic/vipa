<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Ojs\UserBundle\Entity\User;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

/**
 * Subject
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,translations.subject,translations.description")
 */
class Subject extends AbstractTranslatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @Expose
     * @GRID\Column(title="id")
     */
    protected $id;
    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\SubjectTranslation")
     */
    protected $translations;
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
    /** @var ArrayCollection|Subject[] */
    private $children;
    /**
     * @var string
     * @Expose
     * @GRID\Column(title="subject", field="translations.subject", safe=false)
     */
    private $subject;

    /**
     * @var string
     */
    private $indentedSubject;
    /**
     * @var string
     * @Expose
     * @GRID\Column(title="description", field="translations.description", safe=false)
     */
    private $description;
    /**
     * @var ArrayCollection
     */
    private $users;
    /**
     * This data will be pre-calculated with scheduled tasks
     * @var int
     */
    private $totalJournalCount;
    /**
     * @var ArrayCollection
     */
    private $journals;
    /**
     * @var string
     */
    private $slug;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->journals = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    /**
     *
     * @return Subject
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Subject|null $parent
     * @return Subject
     */
    public function setParent(Subject $parent = null)
    {
        $this->parent = $parent;
        if($parent) {
            $parent->addChild($this);
        }
        return $this;
    }

    /**
     * @return ArrayCollection|Subject[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return mixed
     */
    public function getRoot()
    {
        return $this->root;
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getLogicalFieldTranslation('description', false);
    }

    /**
     * Get description translations
     *
     * @return string
     */
    public function getDescriptionTranslations()
    {
        $titles = [];
        /** @var SubjectTranslation $translation */
        foreach($this->translations as $translation){
            if(!empty($translation->getDescription())){
                $titles[] = $translation->getDescription(). ' ['.$translation->getLocale().']';
            }
        }
        return implode('<br>', $titles);
    }

    /**
     * Set description
     *
     * @param  string $description
     * @return Subject
     */
    public function setDescription($description)
    {
        $this->translate()->setDescription($description);

        return $this;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\SubjectTranslation
     */
    public function translate($locale = null)
    {
        if (null === $locale) {
            $locale = $this->currentLocale;
        }

        if (!$locale && $this->parent !== null) {
            $locale = $this->parent->getCurrentLocale();
        }

        if (!$locale) {
            throw new \RuntimeException('No locale has been set and currentLocale is empty');
        }

        /** @var SubjectTranslation $currentTranslation */
        $currentTranslation = $this->currentTranslation;
        if ($currentTranslation && $currentTranslation->getLocale() === $locale) {
            return $currentTranslation;
        }

        /** @var SubjectTranslation $defaultTranslation */
        $defaultTranslation = $this->translations->get($this->getDefaultLocale());
        if (!$translation = $this->translations->get($locale)) {
            $translation = new SubjectTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setSubject($defaultTranslation->getSubject());
                $translation->setDescription($defaultTranslation->getDescription());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }

        $this->currentTranslation = $translation;

        return $translation;
    }

    /**
     * Add users
     *
     * @param  User $users
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
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Get subjects
     * @return ArrayCollection|Journal[]
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
        if (!$this->journals->contains($journal)) {
            $this->journals->add($journal);
        }
        $journal->addSubject($this);

        return $this;
    }

    /**
     * Remove journal
     *
     * @param Journal $journal
     */
    public function removeJournal(Journal $journal)
    {
        if ($this->journals->contains($journal)) {
            $this->journals->removeElement($journal);
        }
        $journal->removeSubject($this);
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

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getSubject();
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->getLogicalFieldTranslation('subject', false);
    }

    /**
     * Get subject translations
     *
     * @return string
     */
    public function getSubjectTranslations()
    {
        $titles = [];
        /** @var SubjectTranslation $translation */
        foreach($this->translations as $translation){
            $titles[] = $translation->getSubject(). ' ['.$translation->getLocale().']';
        }
        return implode('<br>', $titles);
    }

    /**
     * Set subject
     *
     * @param  string $subject
     * @return Subject
     */
    public function setSubject($subject)
    {
        $this->translate()->setSubject($subject);

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
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
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
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
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
     * @param Subject $child
     *
     * @return Subject
     */
    public function addChild(Subject $child)
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
    }

    /**
     * Remove child
     *
     * @param Subject $child
     *
     * @return Subject
     */
    public function removeChild(Subject $child)
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            $child->setParent($this);
        }
        return $this;
    }

    public function getIndentedSubject() {
        return str_repeat(" >> ", $this->lvl) . $this->getSubject();
    }
}
