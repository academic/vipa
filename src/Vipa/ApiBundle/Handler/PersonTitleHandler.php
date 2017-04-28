<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\AdminBundle\Form\Type\PersonTitleType;
use Vipa\JournalBundle\Entity\PersonTitle;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class PersonTitleHandler
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
     * Get a PersonTitle.
     *
     * @param mixed $id
     *
     * @return PersonTitle
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of PersonTitles.
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
     * Create a new PersonTitle.
     *
     * @param array $parameters
     *
     * @return PersonTitle
     */
    public function post(array $parameters)
    {
        $entity = $this->createPersonTitle();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a PersonTitle.
     *
     * @param PersonTitle $entity
     * @param array         $parameters
     *
     * @return PersonTitle
     */
    public function put(PersonTitle $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a PersonTitle.
     *
     * @param PersonTitle $entity
     * @param array         $parameters
     *
     * @return PersonTitle
     */
    public function patch(PersonTitle $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a PersonTitle.
     *
     * @param PersonTitle $entity
     *
     * @return PersonTitle
     */
    public function delete(PersonTitle $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param PersonTitle $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return PersonTitle
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(PersonTitle $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new PersonTitleType(), $entity, array('method' => $method, 'csrf_protection' => false));
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

    private function createPersonTitle()
    {
        return new $this->entityClass();
    }
}