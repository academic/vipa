<?php

namespace Ojs\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Ojs\JournalBundle\Form\Type\JournalThemeType;
use Ojs\JournalBundle\Entity\JournalTheme;
use Ojs\JournalBundle\Service\JournalService;
use Symfony\Component\Form\FormFactoryInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;
use Symfony\Component\Filesystem\Filesystem;

class JournalThemeHandler
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
     * Get a JournalTheme.
     *
     * @param mixed $id
     *
     * @return JournalTheme
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of JournalThemes.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        $selectedJournal = $this->journalService->getSelectedJournal();
        return $this->repository->findBy(array('owner' => $selectedJournal), null, $limit, $offset);
    }

    /**
     * Create a new JournalTheme.
     *
     * @param array $parameters
     *
     * @return JournalTheme
     */
    public function post(array $parameters)
    {
        $entity = $this->createJournalTheme();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a JournalTheme.
     *
     * @param JournalTheme $entity
     * @param array         $parameters
     *
     * @return JournalTheme
     */
    public function put(JournalTheme $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a JournalTheme.
     *
     * @param JournalTheme $entity
     * @param array         $parameters
     *
     * @return JournalTheme
     */
    public function patch(JournalTheme $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a JournalTheme.
     *
     * @param JournalTheme $entity
     *
     * @return JournalTheme
     */
    public function delete(JournalTheme $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param JournalTheme $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return JournalTheme
     *
     * @throws \Ojs\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(JournalTheme $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new JournalThemeType(), $entity, array('method' => $method, 'csrf_protection' => false));
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

    private function createJournalTheme()
    {
        return new $this->entityClass();
    }
}