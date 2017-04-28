<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\AdminBundle\Form\Type\AdminAnnouncementType;
use Vipa\AdminBundle\Entity\AdminAnnouncement;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class AnnouncementHandler
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
     * Get a Announcement.
     *
     * @param mixed $id
     *
     * @return AdminAnnouncement
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Announcements.
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
     * Create a new Announcement.
     *
     * @param array $parameters
     *
     * @return AdminAnnouncement
     */
    public function post(array $parameters)
    {
        $entity = $this->createAnnouncement();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Announcement.
     *
     * @param AdminAnnouncement $entity
     * @param array         $parameters
     *
     * @return AdminAnnouncement
     */
    public function put(AdminAnnouncement $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Announcement.
     *
     * @param AdminAnnouncement $entity
     * @param array         $parameters
     *
     * @return AdminAnnouncement
     */
    public function patch(AdminAnnouncement $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Announcement.
     *
     * @param AdminAnnouncement $entity
     *
     * @return AdminAnnouncement
     */
    public function delete(AdminAnnouncement $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param AdminAnnouncement $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return AdminAnnouncement
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(AdminAnnouncement $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new AdminAnnouncementType(), $entity, array('method' => $method, 'csrf_protection' => false));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $page = $form->getData();
            $this->om->persist($entity);
            $this->om->flush();
            return $page;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createAnnouncement()
    {
        return new $this->entityClass();
    }
}