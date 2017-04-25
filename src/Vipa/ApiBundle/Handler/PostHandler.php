<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\AdminBundle\Form\Type\AdminPostType;
use Vipa\AdminBundle\Entity\AdminPost;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class PostHandler
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
     * Get a Post.
     *
     * @param mixed $id
     *
     * @return AdminPost
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Posts.
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
     * Create a new Post.
     *
     * @param array $parameters
     *
     * @return AdminPost
     */
    public function post(array $parameters)
    {
        $entity = $this->createPost();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Post.
     *
     * @param AdminPost $entity
     * @param array         $parameters
     *
     * @return AdminPost
     */
    public function put(AdminPost $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Post.
     *
     * @param AdminPost $entity
     * @param array         $parameters
     *
     * @return AdminPost
     */
    public function patch(AdminPost $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Post.
     *
     * @param AdminPost $entity
     *
     * @return AdminPost
     */
    public function delete(AdminPost $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param AdminPost $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return AdminPost
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(AdminPost $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new AdminPostType(), $entity, array('method' => $method, 'csrf_protection' => false));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $page = $form->getData();
            $entity->setCurrentLocale('en');
            $entity->setSlug($entity->getTitle());
            $this->om->persist($entity);
            $this->om->flush();
            return $page;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createPost()
    {
        return new $this->entityClass();
    }
}