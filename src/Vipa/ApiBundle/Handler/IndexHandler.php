<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\AdminBundle\Form\Type\IndexType;
use Vipa\JournalBundle\Entity\Index;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class IndexHandler
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
     * Get a Index.
     *
     * @param mixed $id
     *
     * @return Index
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Indexs.
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
     * Create a new Index.
     *
     * @param array $parameters
     *
     * @return Index
     */
    public function post(array $parameters)
    {
        $entity = $this->createIndex();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Index.
     *
     * @param Index $entity
     * @param array         $parameters
     *
     * @return Index
     */
    public function put(Index $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Index.
     *
     * @param Index $entity
     * @param array         $parameters
     *
     * @return Index
     */
    public function patch(Index $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Index.
     *
     * @param Index $entity
     *
     * @return Index
     */
    public function delete(Index $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param Index $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return Index
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(Index $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new IndexType(), $entity, array('method' => $method, 'csrf_protection' => false));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $page = $form->getData();
            $this->om->persist($entity);
            $this->om->flush();
            return $page;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createIndex()
    {
        return new $this->entityClass();
    }
}