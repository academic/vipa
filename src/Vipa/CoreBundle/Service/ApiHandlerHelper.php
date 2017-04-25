<?php

namespace Vipa\CoreBundle\Service;

use Jb\Bundle\FileUploaderBundle\Entity\FileHistory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Common\Annotations\Reader;
use Liip\ImagineBundle\Templating\ImagineExtension;
use Vipa\CoreBundle\Annotation\Display\File;
use Vipa\CoreBundle\Annotation\Display\Image;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class $this
 * @package Vipa\CoreBundle\Service
 */
class ApiHandlerHelper
{
    private $kernel;
    private $reader;
    private $imagine;
    private $requestStack;

    public function __construct(
        KernelInterface $kernel,
        Reader $reader,
        ImagineExtension $imagine,
        RequestStack $requestStack
    ) {
        $this->kernel           = $kernel;
        $this->reader           = $reader;
        $this->imagine          = $imagine;
        $this->requestStack     = $requestStack;
    }

    /**
     * @param $entities[]
     * @return $entities
     */
    public function normalizeEntities($entities)
    {
        foreach($entities as $entityKey => $entity){
            $entities[$entityKey] = $this->normalizeEntity($entity);
        }
        return $entities;
    }

    /**
     * @param $entity
     * @return $entity
     */
    public function normalizeEntity($entity)
    {
        if(is_object($entity)){
            $reflectionClass = new \ReflectionClass($entity);
            foreach($reflectionClass->getProperties() as $property){
                foreach($this->reader->getPropertyAnnotations($property) as $annotation){
                    $getSetter = 'set'.ucfirst($property->name);
                    $getGetter = 'get'.ucfirst($property->name);
                    if(method_exists($entity, $getGetter) && !empty($entity->$getGetter())){
                        if(method_exists($entity->$getGetter(), 'first')){
                            foreach($entity->$getGetter() as $object){
                                $this->normalizeEntity($object);
                            }
                        }
                        if ($annotation instanceof File){
                            if(!preg_match('/http/', $entity->$getGetter())){
                                $fileFullPath = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost().'/uploads/'.$annotation->getPath().'/'.$entity->$getGetter();
                                $entity->$getSetter($fileFullPath);
                            }
                        } elseif ($annotation instanceof Image){
                            if(!preg_match('/http/', $entity->$getGetter())) {
                                $filteredImage = $this->imagine->filter(
                                    $entity->$getGetter(),
                                    $annotation->getFilter()
                                );
                                $entity->$getSetter($filteredImage);
                            }
                        }
                    }
                }
            }
        }
        return $entity;
    }

    /**
     * @return RequestStack
     */
    public function getRequestStack()
    {
        return $this->requestStack;
    }

    /**
     * @param $fileName
     * @param $originalName
     * @param $type
     * @param $em
     * @param bool $save
     *
     * @return FileHistory
     */
    public function createFileHistory($fileName, $originalName, $type, $em, $save = true)
    {
        $fileHistory = new FileHistory();
        $fileHistory
            ->setFileName($fileName)
            ->setOriginalName($originalName)
            ->setType($type)
            ->setUserId(null)
            ;
        $em->persist($fileHistory);
        if($save){
            $em->flush();
        }
        return $fileHistory;
    }
}
