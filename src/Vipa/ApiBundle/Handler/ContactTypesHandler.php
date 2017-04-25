<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Model\ContactTypesInterface;
use Vipa\AdminBundle\Form\Type\ContactTypesType;
use Vipa\ApiBundle\Exception\InvalidFormException;

class ContactTypesHandler implements ContactTypesHandlerInterface
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
     * Get a ContactType.
     *
     * @param mixed $id
     *
     * @return ContactTypesInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of ContactTypes.
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
     * Create a new ContactType.
     *
     * @param array $parameters
     *
     * @return ContactTypesInterface
     */
    public function post(array $parameters)
    {
        $page = $this->createContactType();
        return $this->processForm($page, $parameters, 'POST');
    }

    /**
     * Edit a ContactType.
     *
     * @param ContactTypesInterface $contactType
     * @param array         $parameters
     *
     * @return ContactTypesInterface
     */
    public function put(ContactTypesInterface $contactType, array $parameters)
    {
        return $this->processForm($contactType, $parameters, 'PUT');
    }

    /**
     * Partially update a ContactType.
     *
     * @param ContactTypesInterface $page
     * @param array         $parameters
     *
     * @return ContactTypesInterface
     */
    public function patch(ContactTypesInterface $page, array $parameters)
    {
        return $this->processForm($page, $parameters, 'PATCH');
    }

    /**
     * Delete a ContactType.
     *
     * @param ContactTypesInterface $entity
     *
     * @return ContactTypesInterface
     */
    public function delete(ContactTypesInterface $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param ContactTypesInterface $contactType
     * @param array         $parameters
     * @param String        $method
     *
     * @return ContactTypesInterface
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(ContactTypesInterface $contactType, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new ContactTypesType(), $contactType, array('method' => $method, 'csrf_protection' => false));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $page = $form->getData();
            $this->om->persist($contactType);
            $this->om->flush();
            return $page;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createContactType()
    {
        return new $this->entityClass();
    }
}