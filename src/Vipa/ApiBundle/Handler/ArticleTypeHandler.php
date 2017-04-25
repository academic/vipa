<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\AdminBundle\Form\Type\ArticleTypesType;
use Vipa\JournalBundle\Entity\ArticleTypes;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class ArticleTypeHandler
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
     * Get a ArticleType.
     *
     * @param mixed $id
     *
     * @return ArticleTypes
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of ArticleTypes.
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
     * Create a new ArticleType.
     *
     * @param array $parameters
     *
     * @return ArticleTypes
     */
    public function post(array $parameters)
    {
        $entity = $this->createArticleType();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a ArticleType.
     *
     * @param ArticleTypes $entity
     * @param array         $parameters
     *
     * @return ArticleTypes
     */
    public function put(ArticleTypes $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a ArticleType.
     *
     * @param ArticleTypes $entity
     * @param array         $parameters
     *
     * @return ArticleTypes
     */
    public function patch(ArticleTypes $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a ArticleType.
     *
     * @param ArticleTypes $entity
     *
     * @return ArticleTypes
     */
    public function delete(ArticleTypes $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param ArticleTypes $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return ArticleTypes
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(ArticleTypes $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new ArticleTypesType(), $entity, array('method' => $method, 'csrf_protection' => false));
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

    private function createArticleType()
    {
        return new $this->entityClass();
    }
}