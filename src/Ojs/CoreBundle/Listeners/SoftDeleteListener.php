<?php

namespace Ojs\CoreBundle\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Ojs\CoreBundle\Exception\HasRelationException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class SoftDeleteListener
{
    // Entities which don't need manual removal
    private $ignoredEntities = [
        'Prezent\Doctrine\Translatable\Entity\AbstractTranslation',
        'Ojs\AnalyticsBundle\Entity\Statistic',
    ];

    // Entities whose relations aren't checked
    private $excludedEntities = [
        'Ojs\JournalBundle\Entity\Journal',
        'Ojs\JournalBundle\Entity\JournalUser',
        'Ojs\JournalBundle\Entity\Section',
        'Ojs\JournalBundle\Entity\Article',
        'Ojs\JournalBundle\Entity\Issue',
    ];

    public function preSoftDelete(LifecycleEventArgs $args)
    {
        $this->checkRelations($args);
    }

    protected function checkRelations(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        $entityName = (new \ReflectionClass($entity))->getShortName();

        if (in_array($entityManager->getClassMetadata(get_class($entity))->name, $this->excludedEntities)) {
            return;
        }

        $mappings = $entityManager->getClassMetadata(get_class($entity))->getAssociationMappings();

        foreach ($mappings as $mapping) {
            echo '<pre>';
            var_dump($mapping['type']);
            var_dump(get_class($entity));
            echo '</pre>';
            if ($mapping['type'] === ClassMetadataInfo::ONE_TO_MANY || $mapping['type'] === ClassMetadataInfo::MANY_TO_MANY) {
                $targetEntityMeta = $entityManager->getClassMetadata($mapping['targetEntity']);
                if ($targetEntityMeta->reflClass->getParentClass()) {
                    if (in_array($targetEntityMeta->reflClass->getParentClass()->name, $this->ignoredEntities)) {
                        continue;
                    }
                }

                $accessor = PropertyAccess::createPropertyAccessor();
                $value = $accessor->getValue($entity, $mapping['fieldName']);

                if (count($value) === 0) {
                    continue;
                }


                $exception = new HasRelationException();
                $exception->setEntityName($entityName);
                $exception->setEntity($entity);
                $exception->setMapping($mapping);
                throw $exception;
            }
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $this->checkRelations($args);
    }
}
