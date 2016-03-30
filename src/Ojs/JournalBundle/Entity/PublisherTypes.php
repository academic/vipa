<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

/**
 * PublisherTypes
 * @GRID\Source(columns="id,translations.name,translations.description")
 */
class PublisherTypes extends AbstractTranslatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    protected $id;
    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\PublisherTypesTranslation")
     */
    protected $translations;
    /**
     * @var string
     * @GRID\Column(title="name", field="translations.name", safe=false)
     */
    private $name;
    /**
     * @var string
     * @GRID\Column(title="description", field="translations.description", safe=false)
     */
    private $description;
    /**
     * @var string
     */
    private $slug;
    /**
     * @var ArrayCollection|Publisher[]
     */
    private $publishers;

    public function __construct()
    {
        $this->publishers = new ArrayCollection();
        $this->translations = new ArrayCollection();
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
        /** @var PublisherTypesTranslation $translation */
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
     * @param  string           $description
     * @return PublisherTypes
     */
    public function setDescription($description)
    {
        $this->translate()->setDescription($description);

        return $this;
    }

    /**
     * @return string
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
     * @param  Publisher $publisher
     * @return $this
     */
    public function addPublisher(Publisher $publisher)
    {
        $this->publishers[] = $publisher;

        return $this;
    }

    /**
     * @param  Publisher $publisher
     * @return $this
     */
    public function removePublisher(Publisher $publisher)
    {
        $this->publishers->remove($publisher);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPublishers()
    {
        return $this->publishers;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getLogicalFieldTranslation('name', false);
    }

    /**
     * Get name translations
     *
     * @return string
     */
    public function getNameTranslations()
    {
        $titles = [];
        /** @var PublisherTypesTranslation $translation */
        foreach($this->translations as $translation){
            $titles[] = $translation->getName(). ' ['.$translation->getLocale().']';
        }
        return implode('<br>', $titles);
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return PublisherTypes
     */
    public function setName($name)
    {
        $this->translate()->setName($name);

        return $this;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\PublisherTypesTranslation
     */
    public function translate($locale = null)
    {
        if (null === $locale) {
            $locale = $this->currentLocale;
        }
        if (!$locale) {
            throw new \RuntimeException('No locale has been set and currentLocale is empty');
        }
        if ($this->currentTranslation && $this->currentTranslation->getLocale() === $locale) {
            return $this->currentTranslation;
        }
        $defaultTranslation = $this->translations->get($this->getDefaultLocale());
        if (!$translation = $this->translations->get($locale)) {
            $translation = new PublisherTypesTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setName($defaultTranslation->getName());
                $translation->setDescription($defaultTranslation->getDescription());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }
        $this->currentTranslation = $translation;

        return $translation;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return PublisherTypes
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
     * @return PublisherTypes
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }
}
