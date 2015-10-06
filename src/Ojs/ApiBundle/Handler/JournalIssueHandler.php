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
use Liip\ImagineBundle\Templating\ImagineExtension;
use Ojs\CoreBundle\Annotation\Display\File;
use Ojs\CoreBundle\Annotation\Display\Image;
use Symfony\Component\HttpFoundation\RequestStack;

class JournalIssueHandler
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;
    private $journalService;
    private $kernel;
    private $reader;
    private $imagine;
    private $requestStack;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory, JournalService $journalService, KernelInterface $kernel, Reader $reader, ImagineExtension $imagine, RequestStack $requestStack)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
        $this->journalService = $journalService;
        $this->kernel = $kernel;
        $this->reader = $reader;
        $this->imagine = $imagine;
        $this->requestStack = $requestStack;
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
        return $this->normalizeEntity($entity);
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
        foreach($entities as $entityKey => $entity){
            $entities[$entityKey] = $this->normalizeEntity($entity);
        }
        return $entities;
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
        $rootDir = $this->kernel->getRootDir();
        $issueFileDir = $rootDir . '/../web/uploads/issuefiles/';
        $journalUploadDir = $rootDir . '/../web/uploads/journal/';
        if($isImage) {
            $fileHelper = new FileHelper();
            $generatePath = $fileHelper->generatePath($file['filename'], false);
            if(!is_dir($journalUploadDir.$generatePath) || !is_dir($journalUploadDir.'croped/'.$generatePath)){
                mkdir($journalUploadDir.$generatePath, 0775, true);
                mkdir($journalUploadDir.'croped/'.$generatePath, 0775, true);
            }
            $filePath = $generatePath . $file['filename'];
            file_put_contents($journalUploadDir.$filePath, base64_decode($file['encoded_content']));
            file_put_contents($journalUploadDir.'croped/'.$filePath, base64_decode($file['encoded_content']));
            return $filePath;
        }else{
            $fs = new Filesystem();
            $fs->mkdir($issueFileDir);
            $fs->dumpFile($issueFileDir . $file['filename'], base64_decode($file['encoded_content']));
            return $file['filename'];
        }
    }

    /**
     * @param Issue $entity
     * @return Issue
     */
    private function normalizeEntity(Issue $entity)
    {
        $reflectionClass = new \ReflectionClass($entity);
        foreach($reflectionClass->getProperties() as $property){
            foreach($this->reader->getPropertyAnnotations($property) as $annotation){
                $getSetter = 'set'.ucfirst($property->name);
                $getGetter = 'get'.ucfirst($property->name);
                if(!empty($entity->$getGetter())){
                    if ($annotation instanceof File){
                        $fileFullPath = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost().'/uploads/'.$annotation->getPath().'/'.$entity->$getGetter();
                        $entity->$getSetter($fileFullPath);
                    } elseif ($annotation instanceof Image){
                        $filteredImage = $this->imagine->filter(
                            $entity->$getGetter(),
                            $annotation->getFilter()
                        );
                        $entity->$getSetter($filteredImage);
                    }
                }
            }
        }
        return $entity;
    }

    private function createIssue()
    {
        return new $this->entityClass();
    }
}