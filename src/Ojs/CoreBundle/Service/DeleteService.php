<?php

namespace Ojs\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use Ojs\CoreBundle\Annotation\Delete\DeleteParams;
use Ojs\CoreBundle\Exception\HasRelationException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Translation\TranslatorInterface;

class DeleteService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var  TranslatorInterface
     */
    private $translator;

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
     * @param TranslatorInterface $translator
     */
    public function __construct(RegistryInterface $registry, Reader $reader,TranslatorInterface $translator)
    {
        $this->em = $registry->getManager();
        $this->reader = $reader;
        $this->translator = $translator;
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
     * @throws HasRelationException
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
                $hasRelationException = new HasRelationException();
                $hasRelationException
                    ->setErrorMessage($this->translator->trans('deletion.remove_components_first', [
                            '%field%' => implode(',',$findRelations)
                        ]
                    ));
                throw $hasRelationException;
            }
        }
    }
}
