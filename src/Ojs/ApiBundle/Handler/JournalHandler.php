<?php

namespace Ojs\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Ojs\AdminBundle\Form\Type\JournalType;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Form\FormFactoryInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;
use Doctrine\Common\Annotations\Reader;
use Liip\ImagineBundle\Templating\ImagineExtension;
use Ojs\CoreBundle\Annotation\Display\File;
use Ojs\CoreBundle\Annotation\Display\Image;
use Symfony\Component\HttpFoundation\RequestStack;

class JournalHandler implements JournalHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;
    private $reader;
    private $imagine;
    private $requestStack;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory, Reader $reader, ImagineExtension $imagine, RequestStack $requestStack)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
        $this->reader = $reader;
        $this->imagine = $imagine;
        $this->requestStack = $requestStack;
    }

    /**
     * Get a Journal.
     *
     * @param mixed $id
     *
     * @return Journal
     */
    public function get($id)
    {
        /** @var Journal $entity */
        $entity = $this->repository->find($id);
        return $this->normalizeEntity($entity);
    }

    /**
     * Get a list of Journals.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        $entities =  $this->repository->findBy(array(), null, $limit, $offset);
        foreach($entities as $entityKey => $entity){
            $entities[$entityKey] = $this->normalizeEntity($entity);
        }
        return $entities;
    }

    /**
     * Create a new Journal.
     *
     * @param array $parameters
     *
     * @return Journal
     */
    public function post(array $parameters)
    {
        $entity = $this->createJournal();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Journal.
     *
     * @param Journal $entity
     * @param array         $parameters
     *
     * @return Journal
     */
    public function put(Journal $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Journal.
     *
     * @param Journal $entity
     * @param array         $parameters
     *
     * @return Journal
     */
    public function patch(Journal $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Journal.
     *
     * @param Journal $entity
     *
     * @return Journal
     */
    public function delete(Journal $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param Journal $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return Journal
     *
     * @throws \Ojs\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(Journal $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new JournalType(), $entity, array('method' => $method, 'csrf_protection' => false));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $page = $form->getData();
            $entity->setCurrentLocale('en');
            $this->om->persist($entity);
            $this->om->flush();
            return $page;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    /**
     * @param Journal $entity
     * @return Journal
     */
    private function normalizeEntity(Journal $entity)
    {
        $reflectionClass = new \ReflectionClass($entity);
        foreach($reflectionClass->getProperties() as $property){
            foreach($this->reader->getPropertyAnnotations($property) as $annotation){
                $getSetter = 'set'.ucfirst($property->name);
                $getGetter = 'get'.ucfirst($property->name);
                if(method_exists($entity, $getGetter) && !empty($entity->$getGetter())){
                    if ($annotation instanceof File){
                        $fileFullPath = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost().'/uploads/'.$annotation->getPath().'/'.$entity->$getGetter();
                        $entity->$getSetter($fileFullPath);
                    } elseif ($annotation instanceof Image){
                        $filteredImage = $this->imagine->filter(
                            $entity->$getGetter(),
                            $annotation->getFilter()
                        );
                        $entity->$getSetter($filteredImage);
                    }
                }
            }
        }
        return $entity;
    }

    private function createJournal()
    {
        return new $this->entityClass();
    }
}