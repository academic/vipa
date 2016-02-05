<?php

namespace Ojs\CoreBundle\Service;


use Doctrine\ORM\EntityManager;
use Ojs\CoreBundle\Annotation\Delete\DeleteParams;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Annotations\Reader;

class DeleteService
{
    /**
     * @var  EntityManager
     */
    protected $em;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var
     */
    private $entity;

    /**
     * @var bool
     */
    private $hardDelete = false;

    /**
     * @var array
     */
    private $checkUse = array();

    /**
     * DeleteService constructor.
     * @param RegistryInterface $registry
     * @param Reader $reader
     */
    public function __construct(RegistryInterface $registry, Reader $reader )
    {
        $this->em = $registry->getManager();
        $this->reader = $reader;
    }

    /**
     * @param $entity
     */
    public function check($entity)
    {
        $this->entity = $entity;
        $this->setupAnnotationOptions();
        $this->checkUse();
    }

    /**
     * @return bool
     */
    private function setupAnnotationOptions()
    {
        $reflClass = new \ReflectionClass($this->entity);
        /** @var DeleteParams $deleteAnnotation */
        $deleteAnnotation = $this->reader->getClassAnnotation($reflClass, 'Ojs\\CoreBundle\\Annotation\\Delete\\DeleteParams');
        if($deleteAnnotation == null){
            return false;
        }
        $this->hardDelete = $deleteAnnotation->getHardDelete();
        $this->checkUse = $deleteAnnotation->getCheckUse();
        return true;
    }

    /**
     * @return bool
     */
    private function checkUse()
    {
        if(!count($this->checkUse)){
            return true;
        }
        foreach($this->checkUse as $entityName => $usage){
            $findRelations = $this->em->getRepository($entityName)->findBy([
                $usage['field'] => $this->entity
            ]);
            if(count($findRelations) > 0){
                echo implode(',',$findRelations);
            }
        }
        exit();
    }
}
