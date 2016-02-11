<?php

namespace Ojs\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use Ojs\CoreBundle\Annotation\Delete\DeleteParams;
use Ojs\CoreBundle\Exception\HasRelationException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Yaml\Parser;

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
     * @var \ReflectionClass
     */
    private $entityReflClass;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var  TranslatorInterface
     */
    private $translator;

    /**
     * @var
     */
    private $entity;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var Parser
     */
    private $yaml;

    /**
     * @var array
     */
    private $deleteParams = [];
    /**
     * @var bool
     */
    private $hardDelete = false;

    /**
     * @var array
     */
    private $checkUse = [];

    /**
     * DeleteService constructor.
     * @param RegistryInterface $registry
     * @param Reader $reader
     * @param TranslatorInterface $translator
     */
    public function __construct(RegistryInterface $registry, Reader $reader,TranslatorInterface $translator, $rootDir)
    {
        $this->em = $registry->getManager();
        $this->reader = $reader;
        $this->translator = $translator;
        $this->yaml = new Parser();
        $this->rootDir = $rootDir;
    }

    /**
     * @param $entity
     */
    public function check($entity)
    {
        $this->entity = $entity;
        $this->setupReflClass();
        $this->setupYamlOptions();
        $this->setupAnnotationOptions();
        $this->checkUse();
    }

    /**
     * return void
     */
    private function setupReflClass()
    {
        $this->entityReflClass = new \ReflectionClass($this->entity);
        $this->entityName = $this->entityReflClass->getName();
    }

    /**
     * @return bool
     */
    private function setupAnnotationOptions()
    {
        /** @var DeleteParams $deleteAnnotation */
        $deleteAnnotation = $this->reader->getClassAnnotation($this->entityReflClass, 'Ojs\\CoreBundle\\Annotation\\Delete\\DeleteParams');
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
    private function setupYamlOptions()
    {
        $getDeleteParams = $this->yaml->parse(file_get_contents($this->rootDir.'/config/delete_params.yml'));
        if(!key_exists($this->entityName, $getDeleteParams)){
            return;
        }
        $this->deleteParams = $getDeleteParams[$this->entityName];
        $this->hardDelete = isset($this->deleteParams['hardDelete'])? true: false;
        $this->checkUse = isset($this->deleteParams['checkUse'])? $this->deleteParams['checkUse']: $this->checkUse;
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
