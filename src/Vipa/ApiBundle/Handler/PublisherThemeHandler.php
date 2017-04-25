<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\AdminBundle\Form\Type\PublisherThemeType;
use Vipa\JournalBundle\Entity\PublisherTheme;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class PublisherThemeHandler
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
     * Get a PublisherType.
     *
     * @param mixed $id
     *
     * @return PublisherTheme
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of PublisherTheme.
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
     * Create a new PublisherType.
     *
     * @param array $parameters
     *
     * @return PublisherTheme
     */
    public function post(array $parameters)
    {
        $entity = $this->createPublisherType();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a PublisherType.
     *
     * @param PublisherTheme $entity
     * @param array         $parameters
     *
     * @return PublisherTheme
     */
    public function put(PublisherTheme $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a PublisherType.
     *
     * @param PublisherTheme $entity
     * @param array         $parameters
     *
     * @return PublisherTheme
     */
    public function patch(PublisherTheme $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a PublisherType.
     *
     * @param PublisherTheme $entity
     *
     * @return PublisherTheme
     */
    public function delete(PublisherTheme $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param PublisherTheme $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return PublisherTheme
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(PublisherTheme $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new PublisherThemeType(), $entity, array('method' => $method, 'csrf_protection' => false));
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

    private function createPublisherType()
    {
        return new $this->entityClass();
    }
}