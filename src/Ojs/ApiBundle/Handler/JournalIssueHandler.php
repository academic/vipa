<?php

namespace Ojs\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Ojs\JournalBundle\Form\Type\IssueType;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Service\JournalService;
use Symfony\Component\Form\FormFactoryInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Ojs\CoreBundle\Helper\FileHelper;
use Doctrine\Common\Annotations\Reader;
use Ojs\CoreBundle\Service\ApiHandlerHelper;

class JournalIssueHandler
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;
    private $journalService;
    private $kernel;
    private $apiHelper;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory, JournalService $journalService, KernelInterface $kernel, ApiHandlerHelper $apiHelper)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
        $this->journalService = $journalService;
        $this->kernel = $kernel;
        $this->apiHelper = $apiHelper;
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
        /** @var Issue $entity */
        $entity = $this->repository->find($id);
        return $this->apiHelper->normalizeEntity($entity);
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
        $entities =  $this->repository->findBy(array(), null, $limit, $offset);
        return $this->apiHelper->normalizeEntities($entities);
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
        $formData = $form->getData();
        $fullFile = $formData->getFullFile();

        if(isset($fullFile)){
            $entity->setFullFile($this->storeFile($fullFile));
        }
        $cover = $formData->getCover();
        if(isset($cover)){
            $entity->setCover($this->storeFile($cover, true));
        }
        $header = $formData->getHeader();
        if(isset($header)){
            $entity->setHeader($this->storeFile($header, true));
        }
        if ($form->isValid()) {
            $entity->setCurrentLocale('en');
            $entity->setJournal($this->journalService->getSelectedJournal());
            $this->om->persist($entity);
            $this->om->flush();
            return $formData;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function storeFile($file, $isImage = false)
    {
        $fs = new Filesystem();
        $rootDir = $this->kernel->getRootDir();
        $issueFileDir = $rootDir . '/../web/uploads/issuefiles/';
        $journalUploadDir = $rootDir . '/../web/uploads/journal/';
        $fileHelper = new FileHelper();
        $randomPath = $fileHelper->generateRandomPath();
        $generateRandomPath = $randomPath.$file['filename'];
        if($isImage) {
            $fs->dumpFile($journalUploadDir.$generateRandomPath, base64_decode($file['encoded_content']));
            $fs->dumpFile($journalUploadDir.'croped/'.$generateRandomPath, base64_decode($file['encoded_content']));
            return $generateRandomPath;
        }else{
            $fs->dumpFile($issueFileDir . $generateRandomPath, base64_decode($file['encoded_content']));
            return $generateRandomPath;

        }
    }

    private function createIssue()
    {
        return new $this->entityClass();
    }
}