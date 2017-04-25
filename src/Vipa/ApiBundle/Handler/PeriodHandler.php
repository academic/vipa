<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\AdminBundle\Form\Type\PeriodType;
use Vipa\JournalBundle\Entity\Period;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class PeriodHandler
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
     * Get a Period.
     *
     * @param mixed $id
     *
     * @return Period
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Periods.
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
     * Create a new Period.
     *
     * @param array $parameters
     *
     * @return Period
     */
    public function post(array $parameters)
    {
        $entity = $this->createPeriod();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Period.
     *
     * @param Period $entity
     * @param array         $parameters
     *
     * @return Period
     */
    public function put(Period $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Period.
     *
     * @param Period $entity
     * @param array         $parameters
     *
     * @return Period
     */
    public function patch(Period $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Period.
     *
     * @param Period $entity
     *
     * @return Period
     */
    public function delete(Period $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param Period $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return Period
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(Period $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new PeriodType(), $entity, array('method' => $method, 'csrf_protection' => false));
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

    private function createPeriod()
    {
        return new $this->entityClass();
    }
}