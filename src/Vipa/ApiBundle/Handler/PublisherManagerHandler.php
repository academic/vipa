<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\AdminBundle\Form\Type\PublisherManagersType;
use Vipa\AdminBundle\Entity\PublisherManagers;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class PublisherManagerHandler
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
     * Get a PublisherManager.
     *
     * @param mixed $id
     *
     * @return PublisherManagers
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of PublisherManager.
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
     * Create a new PublisherManager.
     *
     * @param array $parameters
     *
     * @return PublisherManagers
     */
    public function post(array $parameters)
    {
        $entity = $this->createPublisherManager();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a PublisherManager.
     *
     * @param PublisherManagers $entity
     * @param array         $parameters
     *
     * @return PublisherManagers
     */
    public function put(PublisherManagers $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a PublisherManager.
     *
     * @param PublisherManagers $entity
     * @param array         $parameters
     *
     * @return PublisherManagers
     */
    public function patch(PublisherManagers $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a PublisherManager.
     *
     * @param PublisherManagers $entity
     *
     * @return PublisherManagers
     */
    public function delete(PublisherManagers $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param PublisherManagers $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return PublisherManagers
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(PublisherManagers $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new PublisherManagersType(), $entity, array('method' => $method, 'csrf_protection' => false));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $page = $form->getData();
            $this->om->persist($entity);
            $this->om->flush();
            return $page;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createPublisherManager()
    {
        return new $this->entityClass();
    }
}