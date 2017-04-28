<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\AdminBundle\Form\Type\LangType;
use Vipa\JournalBundle\Entity\Lang;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class LangHandler
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
     * Get a Lang.
     *
     * @param mixed $id
     *
     * @return Lang
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Langs.
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
     * Create a new Lang.
     *
     * @param array $parameters
     *
     * @return Lang
     */
    public function post(array $parameters)
    {
        $entity = $this->createLang();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Lang.
     *
     * @param Lang $entity
     * @param array         $parameters
     *
     * @return Lang
     */
    public function put(Lang $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Lang.
     *
     * @param Lang $entity
     * @param array         $parameters
     *
     * @return Lang
     */
    public function patch(Lang $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Lang.
     *
     * @param Lang $entity
     *
     * @return Lang
     */
    public function delete(Lang $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param Lang $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return Lang
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(Lang $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new LangType(), $entity, array('method' => $method, 'csrf_protection' => false));
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

    private function createLang()
    {
        return new $this->entityClass();
    }
}