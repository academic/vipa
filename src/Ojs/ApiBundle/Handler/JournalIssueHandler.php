<?php

namespace Ojs\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Ojs\JournalBundle\Form\Type\IssueType;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Service\JournalService;
use Symfony\Component\Form\FormFactoryInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;

class JournalIssueHandler
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;
    private $journalService;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory, JournalService $journalService)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
        $this->journalService = $journalService;
    }

    /**
     * Get a Issue.
     *
     * @param mixed $id
     *
     * @return Issue
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Issues.
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
     * Create a new Issue.
     *
     * @param array $parameters
     *
     * @return Issue
     */
    public function post(array $parameters)
    {
        $entity = $this->createIssue();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Issue.
     *
     * @param Issue $entity
     * @param array         $parameters
     *
     * @return Issue
     */
    public function put(Issue $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Issue.
     *
     * @param Issue $entity
     * @param array         $parameters
     *
     * @return Issue
     */
    public function patch(Issue $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Issue.
     *
     * @param Issue $entity
     *
     * @return Issue
     */
    public function delete(Issue $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param Issue $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return Issue
     *
     * @throws \Ojs\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(Issue $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new IssueType(), $entity, array('method' => $method, 'csrf_protection' => false));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $page = $form->getData();
            $entity->setCurrentLocale('en');
            $entity->setJournal($this->journalService->getSelectedJournal());
            var_dump($this->journalService->getSelectedJournal()->getTitle());
            $this->om->persist($entity);
            $this->om->flush();
            return $page;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createIssue()
    {
        return new $this->entityClass();
    }
}