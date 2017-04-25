<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\AdminBundle\Form\Type\InstitutionType;
use Vipa\JournalBundle\Entity\Institution;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class InstitutionHandler
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
     * Get a Institution.
     *
     * @param mixed $id
     *
     * @return Institution
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Institutions.
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
     * Create a new Institution.
     *
     * @param array $parameters
     *
     * @return Institution
     */
    public function post(array $parameters)
    {
        $entity = $this->createInstitution();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Institution.
     *
     * @param Institution $entity
     * @param array         $parameters
     *
     * @return Institution
     */
    public function put(Institution $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Institution.
     *
     * @param Institution $entity
     * @param array         $parameters
     *
     * @return Institution
     */
    public function patch(Institution $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Institution.
     *
     * @param Institution $entity
     *
     * @return Institution
     */
    public function delete(Institution $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param Institution $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return Institution
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(Institution $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new InstitutionType(), $entity, array('method' => $method, 'csrf_protection' => false));
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

    private function createInstitution()
    {
        return new $this->entityClass();
    }
}