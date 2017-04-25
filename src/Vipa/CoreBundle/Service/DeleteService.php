<?php

namespace Vipa\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use Vipa\CoreBundle\Exception\HasRelationException;
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
     * @var array
     */
    private $allOptions = [];

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
     * @var array
     */
    private $bundles = [];

    /**
     * DeleteService constructor.
     * @param RegistryInterface $registry
     * @param Reader $reader
     * @param TranslatorInterface $translator
     */
    public function __construct(RegistryInterface $registry, Reader $reader,TranslatorInterface $translator, $rootDir, $bundles)
    {
        $this->em = $registry->getManager();
        $this->reader = $reader;
        $this->translator = $translator;
        $this->yaml = new Parser();
        $this->rootDir = $rootDir;
        $this->bundles = $bundles;
    }

    /**
     * @param $entity
     */
    public function check($entity)
    {
        $this->entity = $entity;
        $this->setupReflClass();
        $this->loadAllOptions();
        $this->setupYamlOptions();
        $this->checkUse();
        $this->preDelete();
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
    private function loadAllOptions()
    {
        $getBaseDeleteParams = $this->yaml->parse(file_get_contents($this->rootDir.'/config/delete_params.yml'));
        $this->allOptions = $getBaseDeleteParams;
        foreach($this->bundles as $bundle => $class){
            $reflection = new \ReflectionClass($class);
            if (file_exists($deleteParamsFile = dirname($reflection->getFilename()).'/Resources/config/delete_params.yml')) {
                $parseYamlContent = $this->yaml->parse(file_get_contents($deleteParamsFile));
                if(is_array($parseYamlContent)){
                    $this->allOptions = array_merge($this->allOptions, $parseYamlContent);
                }
            }
        }
    }

    /**
     * @return bool
     */
    private function setupYamlOptions()
    {
        if(!array_key_exists($this->entityName, $this->allOptions)){
            return;
        }
        $this->deleteParams = $this->allOptions[$this->entityName];
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
                            '%field%' => "\n - ".implode("\n - ",$relationStrings)
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
