<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\JournalBundle\Form\Type\BoardType;
use Vipa\JournalBundle\Entity\Board;
use Vipa\JournalBundle\Service\JournalService;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;
use Symfony\Component\Filesystem\Filesystem;

class JournalBoardHandler
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
     * Get a Board.
     *
     * @param mixed $id
     *
     * @return Board
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Boards.
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
     * Create a new Board.
     *
     * @param array $parameters
     *
     * @return Board
     */
    public function post(array $parameters)
    {
        $entity = $this->createBoard();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Board.
     *
     * @param Board $entity
     * @param array         $parameters
     *
     * @return Board
     */
    public function put(Board $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Board.
     *
     * @param Board $entity
     * @param array         $parameters
     *
     * @return Board
     */
    public function patch(Board $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Board.
     *
     * @param Board $entity
     *
     * @return Board
     */
    public function delete(Board $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param Board $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return Board
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(Board $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new BoardType(), $entity, array('method' => $method, 'csrf_protection' => false));
        $form->submit($parameters, 'PATCH' !== $method);
        $formData = $form->getData();

        if ($form->isValid()) {
            $entity->setCurrentLocale('en');
            $entity->setJournal($this->journalService->getSelectedJournal());
            $this->om->persist($entity);
            $this->om->flush();
            return $formData;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createBoard()
    {
        return new $this->entityClass();
    }
}