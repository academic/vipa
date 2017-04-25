<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\AdminBundle\Form\Type\UserType;
use Vipa\UserBundle\Entity\User;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class UserHandler
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
     * Get a User.
     *
     * @param mixed $id
     *
     * @return User
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Users.
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
     * Create a new User.
     *
     * @param array $parameters
     *
     * @return User
     */
    public function post(array $parameters)
    {
        $entity = $this->createUser();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a User.
     *
     * @param User $entity
     * @param array         $parameters
     *
     * @return User
     */
    public function put(User $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a User.
     *
     * @param User $entity
     * @param array         $parameters
     *
     * @return User
     */
    public function patch(User $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a User.
     *
     * @param User $entity
     *
     * @return User
     */
    public function delete(User $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param User $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return User
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(User $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new UserType(), $entity, array('method' => $method, 'csrf_protection' => false));
        $form->remove('password');
        $entity->setPassword($this->generateRandomString());
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $page = $form->getData();
            $this->om->persist($entity);
            $this->om->flush();
            return $page;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createUser()
    {
        return new $this->entityClass();
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}