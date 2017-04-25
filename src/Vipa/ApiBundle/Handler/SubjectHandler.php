<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\AdminBundle\Form\Type\SubjectType;
use Vipa\JournalBundle\Entity\Subject;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class SubjectHandler
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
     * Get a Subject.
     *
     * @param mixed $id
     *
     * @return Subject
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Subjects.
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
     * Create a new Subject.
     *
     * @param array $parameters
     *
     * @return Subject
     */
    public function post(array $parameters)
    {
        $entity = $this->createSubject();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Subject.
     *
     * @param Subject $entity
     * @param array         $parameters
     *
     * @return Subject
     */
    public function put(Subject $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Subject.
     *
     * @param Subject $entity
     * @param array         $parameters
     *
     * @return Subject
     */
    public function patch(Subject $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Subject.
     *
     * @param Subject $entity
     *
     * @return Subject
     */
    public function delete(Subject $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param Subject $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return Subject
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(Subject $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new SubjectType(), $entity, array('method' => $method, 'csrf_protection' => false));
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

    private function createSubject()
    {
        return new $this->entityClass();
    }
}