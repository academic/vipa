<?php

namespace Ojs\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Ojs\AdminBundle\Entity\AdminPage;
use Ojs\AdminBundle\Form\Type\AdminPageType;
use Symfony\Component\Form\FormFactoryInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;

class PageHandler
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
     * Get a Page.
     *
     * @param mixed $id
     *
     * @return AdminPage
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Pages.
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
     * Create a new Page.
     *
     * @param array $parameters
     *
     * @return AdminPage
     */
    public function post(array $parameters)
    {
        $entity = $this->createPage();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Page.
     *
     * @param AdminPage $entity
     * @param array         $parameters
     *
     * @return AdminPage
     */
    public function put(AdminPage $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Page.
     *
     * @param AdminPage $entity
     * @param array         $parameters
     *
     * @return AdminPage
     */
    public function patch(AdminPage $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Page.
     *
     * @param AdminPage $entity
     *
     * @return AdminPage
     */
    public function delete(AdminPage $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param AdminPage $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return AdminPage
     *
     * @throws \Ojs\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(AdminPage $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new AdminPageType(), $entity, array('method' => $method, 'csrf_protection' => false));
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

    private function createPage()
    {
        return new $this->entityClass();
    }
}