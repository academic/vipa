<?php

namespace Ojs\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Ojs\AdminBundle\Form\Type\JournalType;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Form\FormFactoryInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;
use Doctrine\Common\Annotations\Reader;
use Ojs\CoreBundle\Service\ApiHandlerHelper;

class JournalHandler implements JournalHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;
    private $apiHelper;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory, ApiHandlerHelper $apiHelper)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
        $this->apiHelper = $apiHelper;
    }

    /**
     * Get a Journal.
     *
     * @param mixed $id
     * @param bool $normalize
     *
     * @return Journal
     */
    public function get($id, $normalize = false)
    {
        /** @var Journal $entity */
        $entity = $this->repository->find($id);
        if(!$normalize){
            return $entity;
        }
        return $this->apiHelper->normalizeEntity($entity);
    }

    /**
     * Get a list of Journals.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        $entities =  $this->repository->findBy(array(), null, $limit, $offset);
        return $this->apiHelper->normalizeEntities($entities);
    }

    /**
     * Create a new Journal.
     *
     * @param array $parameters
     *
     * @return Journal
     */
    public function post(array $parameters)
    {
        $entity = $this->createJournal();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Journal.
     *
     * @param Journal $entity
     * @param array         $parameters
     *
     * @return Journal
     */
    public function put(Journal $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Journal.
     *
     * @param Journal $entity
     * @param array         $parameters
     *
     * @return Journal
     */
    public function patch(Journal $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Journal.
     *
     * @param Journal $entity
     *
     * @return Journal
     */
    public function delete(Journal $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param Journal $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return Journal
     *
     * @throws \Ojs\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(Journal $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new JournalType(), $entity, array('method' => $method, 'csrf_protection' => false));
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

    private function createJournal()
    {
        return new $this->entityClass();
    }
}