<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\AdminBundle\Form\Type\PublisherTypesType;
use Vipa\JournalBundle\Entity\PublisherTypes;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class PublisherTypeHandler
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * Get a PublisherType.
     *
     * @param mixed $id
     *
     * @return PublisherTypes
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of PublisherTypes.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * Create a new PublisherType.
     *
     * @param array $parameters
     *
     * @return PublisherTypes
     */
    public function post(array $parameters)
    {
        $entity = $this->createPublisherType();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a PublisherType.
     *
     * @param PublisherTypes $entity
     * @param array         $parameters
     *
     * @return PublisherTypes
     */
    public function put(PublisherTypes $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a PublisherType.
     *
     * @param PublisherTypes $entity
     * @param array         $parameters
     *
     * @return PublisherTypes
     */
    public function patch(PublisherTypes $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a PublisherType.
     *
     * @param PublisherTypes $entity
     *
     * @return PublisherTypes
     */
    public function delete(PublisherTypes $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param PublisherTypes $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return PublisherTypes
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(PublisherTypes $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new PublisherTypesType(), $entity, array('method' => $method, 'csrf_protection' => false));
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

    private function createPublisherType()
    {
        return new $this->entityClass();
    }
}