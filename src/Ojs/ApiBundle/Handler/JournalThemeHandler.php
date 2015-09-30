<?php

namespace Ojs\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Ojs\JournalBundle\Form\Type\ThemeType;
use Ojs\JournalBundle\Entity\Theme;
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
     * Get a Theme.
     *
     * @param mixed $id
     *
     * @return Theme
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Themes.
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
     * Create a new Theme.
     *
     * @param array $parameters
     *
     * @return Theme
     */
    public function post(array $parameters)
    {
        $entity = $this->createTheme();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Theme.
     *
     * @param Theme $entity
     * @param array         $parameters
     *
     * @return Theme
     */
    public function put(Theme $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Theme.
     *
     * @param Theme $entity
     * @param array         $parameters
     *
     * @return Theme
     */
    public function patch(Theme $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Theme.
     *
     * @param Theme $entity
     *
     * @return Theme
     */
    public function delete(Theme $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param Theme $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return Theme
     *
     * @throws \Ojs\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(Theme $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new ThemeType(), $entity, array('method' => $method, 'csrf_protection' => false));
        $form->submit($parameters, 'PATCH' !== $method);
        $formData = $form->getData();

        if ($form->isValid()) {
            $entity->setCurrentLocale('en');
            $entity->setOwner($this->journalService->getSelectedJournal());
            $this->om->persist($entity);
            $this->om->flush();
            return $formData;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createTheme()
    {
        return new $this->entityClass();
    }
}