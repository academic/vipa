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
     * @var array
     */
    private $preDelete = [];

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
        $this->preDelete();
        $this->checkUse();
    }

    /**
     * return void
     */
    private function setupReflClass()
    {
        $this->entityReflClass = new \ReflectionClass($this->entity);
        $this->entityName = $this->em->getClassMetadata(get_class($this->entity))->getName();
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
        $this->hardDelete = isset($this->deleteParams['hardDelete'])? true: $this->hardDelete;
        $this->checkUse = isset($this->deleteParams['checkUse'])? $this->deleteParams['checkUse']: $this->checkUse;
        $this->preDelete = isset($this->deleteParams['preDelete'])? $this->deleteParams['preDelete']: $this->preDelete;
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
        foreach($this->checkUse as $usage){
            $findRelations = $this->findRelations($usage);
            if(count($findRelations) > 0){
                $relationStrings = [];
                foreach($findRelations as $relation){
                    $relationRefl = new \ReflectionClass($relation);
                    $relationStrings[] = (string)$relation.'['.$relationRefl->getShortName().'-'.$usage['field'].'#'.$relation->getId().']';
                }
                $hasRelationException = new HasRelationException();
                $hasRelationException
                    ->setErrorMessage($this->translator->trans('deletion.remove_components_first', [
                            '%field%' => implode(',',$relationStrings)
                        ]
                    ));
                throw $hasRelationException;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    private function preDelete()
    {
        if(!count($this->preDelete)){
            return true;
        }
        foreach($this->preDelete as $usage){
            $findRelations = $this->findRelations($usage);
            if(count($findRelations) > 0){
                foreach($findRelations as $relation){
                    $this->em->remove($relation);
                }
            }
        }
        $this->em->flush();
        return true;
    }

    /**
     * @param array $usage
     * @return array
     */
    private function findRelations($usage)
    {
        if(isset($usage['type']) && $usage['type'] == 'm2m'){
            exit('m2m baby');
            $repo = $this->em->getRepository($usage['entityName']);
            $qb = $repo->createQueryBuilder('a');
            $qb
                ->andWhere(':entity MEMBER OF a.'.$usage['field'])
                ->setParameter('entity', $this->entity)
            ;
            $findRelations = $qb->getQuery()->getResult();
        }else{
            $findRelations = $this->em->getRepository($usage['entityName'])->findBy([
                $usage['field'] => $this->entity
            ]);
        }
        return $findRelations;
    }
}
