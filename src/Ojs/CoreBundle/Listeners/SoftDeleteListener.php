<?php

namespace Ojs\CoreBundle\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Ojs\CoreBundle\Exception\ChildNotEmptyException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class SoftDeleteListener
{
    private $excludedParents = [
        'Prezent\Doctrine\Translatable\Entity\AbstractTranslation',
        'Ojs\AnalyticsBundle\Entity\Statistic'
    ];

    public function preSoftDelete(LifecycleEventArgs $args)
    {
        $this->checkRelations($args);
    }

    protected function checkRelations(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        $mappings = $entityManager->getClassMetadata(get_class($entity))->getAssociationMappings();
        foreach ($mappings as $mapping) {
            $type = $mapping['type'];
            if ($type === ClassMetadataInfo::ONE_TO_MANY || $type === ClassMetadataInfo::MANY_TO_MANY) {
                $targetEntityMeta = $entityManager->getClassMetadata($mapping['targetEntity']);
                if($targetEntityMeta->reflClass->getParentClass()){
                    if (in_array($targetEntityMeta->reflClass->getParentClass()->name, $this->excludedParents)) {
                        continue;
                    }
                }

                $accessor = PropertyAccess::createPropertyAccessor();
                $value = $accessor->getValue($entity, $mapping['fieldName']);
                if (count($value) === 0) {
                    continue;
                }
                $exception = new ChildNotEmptyException();
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
