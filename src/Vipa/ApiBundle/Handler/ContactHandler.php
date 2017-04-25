<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\AdminBundle\Form\Type\ContactType;
use Vipa\JournalBundle\Entity\JournalContact;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class ContactHandler
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
     * Get a Contact.
     *
     * @param mixed $id
     *
     * @return JournalContact
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Contacts.
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
     * Create a new Contact.
     *
     * @param array $parameters
     *
     * @return JournalContact
     */
    public function post(array $parameters)
    {
        $entity = $this->createContact();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Contact.
     *
     * @param JournalContact $entity
     * @param array         $parameters
     *
     * @return JournalContact
     */
    public function put(JournalContact $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Contact.
     *
     * @param JournalContact $entity
     * @param array         $parameters
     *
     * @return JournalContact
     */
    public function patch(JournalContact $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Contact.
     *
     * @param JournalContact $entity
     *
     * @return JournalContact
     */
    public function delete(JournalContact $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param JournalContact $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return JournalContact
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(JournalContact $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new ContactType(), $entity, array('method' => $method, 'csrf_protection' => false));
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

    private function createContact()
    {
        return new $this->entityClass();
    }
}