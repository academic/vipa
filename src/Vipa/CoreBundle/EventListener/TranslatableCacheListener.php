<?php

namespace Vipa\CoreBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Query;
use Metadata\MetadataFactory;
use Prezent\Doctrine\Translatable\EventListener\TranslatableListener;
use Prezent\Doctrine\Translatable\Mapping\TranslatableMetadata;
use Prezent\Doctrine\Translatable\Mapping\TranslationMetadata;

class TranslatableCacheListener extends TranslatableListener
{
    /**
     * @var string Locale to use for translations
     */
    private $currentLocale = 'en';

    /**
     * @var string Locale to use when the current locale is not available
     */
    private $fallbackLocale = 'en';

    /**
     * @var MetadataFactory
     */
    private $metadataFactory;

    /**
     * @var array
     */
    private $cache = array();

    /**
     * Constructor
     *
     * @param MetadataFactory $factory
     */
    public function __construct(MetadataFactory $factory)
    {
        $this->metadataFactory = $factory;
    }

    /**
     * Get the current locale
     *
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    /**
     * Set the current locale
     *
     * @param string $currentLocale
     * @return self
     */
    public function setCurrentLocale($currentLocale)
    {
        $this->currentLocale = $currentLocale;

        return $this;
    }

    /**
     * Get the fallback locale
     *
     * @return string
     */
    public function getFallbackLocale()
    {
        return $this->fallbackLocale;
    }

    /**
     * Set the fallback locale
     *
     * @param string $fallbackLocale
     * @return self
     */
    public function setFallbackLocale($fallbackLocale)
    {
        $this->fallbackLocale = $fallbackLocale;

        return $this;
    }

    /**
     * Getter for metadataFactory
     *
     * @return MetadataFactory
     */
    public function getMetadataFactory()
    {
        return $this->metadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata,
            Events::postLoad,
        );
    }

    /**
     * Add mapping to translatable entities
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     * @return void
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $reflClass = $classMetadata->reflClass;

        if (!$reflClass || $reflClass->isAbstract()) {
            return;
        }

        if ($reflClass->implementsInterface('Prezent\Doctrine\Translatable\TranslatableInterface')) {
            $this->mapTranslatable($classMetadata);
        }

        if ($reflClass->implementsInterface('Prezent\Doctrine\Translatable\TranslationInterface')) {
            $this->mapTranslation($classMetadata);
        }
    }

    /**
     * Add mapping data to a translatable entity
     *
     * @param ClassMetadata $mapping
     * @return void
     */
    private function mapTranslatable(ClassMetadata $mapping)
    {
        $metadata = $this->getTranslatableMetadata($mapping->name);
        if (!$mapping->hasAssociation($metadata->translations->name)) {
            $targetMetadata = $this->getTranslatableMetadata($metadata->targetEntity);

            $mapping->mapOneToMany(
                array(
                    'fieldName' => $metadata->translations->name,
                    'targetEntity' => $metadata->targetEntity,
                    'mappedBy' => $targetMetadata->translatable->name,
                    'fetch' => ClassMetadataInfo::FETCH_LAZY,
                    'indexBy' => $targetMetadata->locale->name,
                    'cascade' => array('persist', 'merge', 'remove'),
                    'orphanRemoval' => true,
                )
            );

            $cacheMap = array(
                'usage' => constant('Doctrine\ORM\Mapping\ClassMetadata::CACHE_USAGE_'.'NONSTRICT_READ_WRITE'),
            );
            $mapping->enableAssociationCache($metadata->translations->name, $cacheMap);
        }
    }

    /**
     * Get translatable metadata
     *
     * @param string $className
     * @return TranslatableMetadata|TranslationMetadata
     */
    public function getTranslatableMetadata($className)
    {
        if (array_key_exists($className, $this->cache)) {
            return $this->cache[$className];
        }

        if ($metadata = $this->metadataFactory->getMetadataForClass($className)) {
            $metadata->validate();
        }

        $this->cache[$className] = $metadata;

        return $metadata;
    }

    /**
     * Add mapping data to a translation entity
     *
     * @param ClassMetadata $mapping
     * @return void
     */
    private function mapTranslation(ClassMetadata $mapping)
    {
        $metadata = $this->getTranslatableMetadata($mapping->name);

        // Map translatable relation
        if (!$mapping->hasAssociation($metadata->translatable->name)) {
            $targetMetadata = $this->getTranslatableMetadata($metadata->targetEntity);

            $mapping->mapManyToOne(
                array(
                    'fieldName' => $metadata->translatable->name,
                    'targetEntity' => $metadata->targetEntity,
                    'inversedBy' => $targetMetadata->translations->name,
                    'joinColumns' => array(
                        array(
                            'name' => 'translatable_id',
                            'referencedColumnName' => 'id',
                            'onDelete' => 'CASCADE',
                            'nullable' => false,
                        )
                    ),
                )
            );
        }

        // Map locale field
        if (!$mapping->hasField($metadata->locale->name)) {
            $mapping->mapField(
                array(
                    'fieldName' => $metadata->locale->name,
                    'type' => 'string',
                )
            );
        }

        // Map unique index
        $columns = array(
            $mapping->getSingleAssociationJoinColumnName($metadata->translatable->name),
            $metadata->locale->name,
        );

        if (!$this->hasUniqueConstraint($mapping, $columns)) {
            $constraints = isset($mapping->table['uniqueConstraints']) ? $mapping->table['uniqueConstraints'] : array();
            $constraints[$mapping->getTableName().'_uniq_trans'] = array(
                'columns' => $columns,
            );

            $mapping->setPrimaryTable(
                array(
                    'uniqueConstraints' => $constraints,
                )
            );
        }
    }

    /**
     * Check if an unique constraint has been defined
     *
     * @param ClassMetadata $mapping
     * @param array $columns
     * @return bool
     */
    private function hasUniqueConstraint(ClassMetadata $mapping, array $columns)
    {
        if (!isset($mapping->table['uniqueConstraints'])) {
            return false;
        }

        foreach ($mapping->table['uniqueConstraints'] as $constraint) {
            if (!array_diff($constraint['columns'], $columns)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Load translations
     *
     * @param LifecycleEventArgs $args
     * @return void
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $metadata = $this->getTranslatableMetadata(get_class($entity));

        if ($metadata instanceof TranslatableMetadata) {

            if ($metadata->fallbackLocale) {
                $metadata->fallbackLocale->setValue($entity, $this->fallbackLocale);
            }

            if ($metadata->currentLocale) {
                $metadata->currentLocale->setValue($entity, $this->currentLocale);
            }
        }
    }
}
