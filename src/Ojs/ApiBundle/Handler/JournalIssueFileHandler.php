<?php

namespace Ojs\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Jb\Bundle\FileUploaderBundle\Entity\FileHistory;
use Ojs\CoreBundle\Helper\FileHelper;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\IssueFile;
use Ojs\JournalBundle\Form\Type\IssueFileType;
use Ojs\JournalBundle\Service\JournalService;
use Symfony\Component\Form\FormFactoryInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;
use Symfony\Component\Filesystem\Filesystem;
use Ojs\CoreBundle\Service\ApiHandlerHelper;
use Symfony\Component\HttpKernel\KernelInterface;

class JournalIssueFileHandler
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
     * Get a IssueFile.
     *
     * @param mixed $id
     * @param bool $normalize
     *
     * @return IssueFile
     */
    public function get($id, $normalize = false)
    {
        /** @var IssueFile $entity */
        $entity = $this->repository->find($id);
        if(!$normalize){
            return $entity;
        }
        return $this->apiHelper->normalizeEntity($entity);
    }

    /**
     * Get a list of IssueFiles.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy([
            'issue' => $this->getIssue()
        ], null, $limit, $offset);
    }

    /**
     * Create a new IssueFile.
     *
     * @param array $parameters
     *
     * @return IssueFile
     */
    public function post(array $parameters)
    {
        $entity = $this->createIssueFile();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a IssueFile.
     *
     * @param IssueFile $entity
     * @param array         $parameters
     *
     * @return IssueFile
     */
    public function put(IssueFile $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a IssueFile.
     *
     * @param IssueFile $entity
     * @param array         $parameters
     *
     * @return IssueFile
     */
    public function patch(IssueFile $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a IssueFile.
     *
     * @param IssueFile $entity
     *
     * @return IssueFile
     */
    public function delete(IssueFile $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Get Issue.
     *
     * @return Issue
     */
    public function getIssue()
    {
        $request = $this->apiHelper->getRequestStack()->getCurrentRequest();
        if(!$request) {
            return false;
        }
        $issueId = $request->attributes->get('issueId');
        if (!$issueId) {
            return false;
        }
        /** @var Issue $selectedIssue */
        $selectedIssue= $this->om->getRepository('OjsJournalBundle:Issue')->findOneBy([
            'id' => $issueId,
            'journal' => $this->journalService->getSelectedJournal()
        ]);
        if (!$selectedIssue) {
            return false;
        }
        return $selectedIssue;
    }

    /**
     * Processes the form.
     *
     * @param IssueFile $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return IssueFile
     *
     * @throws \Ojs\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(IssueFile $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new IssueFileType(), $entity, array(
            'method' => $method,
            'csrf_protection' => false,
        ));
        $form->submit($parameters, 'PATCH' !== $method);
        $formData = $form->getData();

        $file = $formData->getFile();
        if(isset($file)){
            $entity->setFile($this->storeFile($file));
        }
        if ($form->isValid()) {
            $entity->setIssue($this->getIssue());

            $this->om->persist($entity);
            $this->om->flush();
            return $formData;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function storeFile($file)
    {
        if(!is_array($file)){
            return $file;
        }
        $fileHelper = new FileHelper();
        $rootDir = $this->kernel->getRootDir();
        $issueFileDir = $rootDir . '/../web/uploads/issuefiles/';
        $generateUniqueFilePath = $fileHelper->generateRandomPath() . $file['filename'];

        $fs = new Filesystem();
        $fs->mkdir($issueFileDir);
        $fs->dumpFile($issueFileDir.$generateUniqueFilePath, base64_decode($file['encoded_content']));
        $this->apiHelper->createFileHistory($generateUniqueFilePath, $generateUniqueFilePath, 'issuefiles', $this->om, true);
        return $generateUniqueFilePath;
    }

    private function createIssueFile()
    {
        return new $this->entityClass();
    }
}
