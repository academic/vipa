<?php

namespace Ojs\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Ojs\CoreBundle\Params\ArticleStatuses;
use Ojs\JournalBundle\Form\Type\ArticleType;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Service\JournalService;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormFactoryInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;
use Symfony\Component\Filesystem\Filesystem;
use Ojs\CoreBundle\Service\ApiHandlerHelper;
use Symfony\Component\HttpKernel\KernelInterface;
use Ojs\CoreBundle\Helper\FileHelper;
use Doctrine\Common\Annotations\Reader;

class JournalArticleHandler
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
     * Get a Article.
     *
     * @param mixed $id
     * @param bool $normalize
     *
     * @return Article
     */
    public function get($id, $normalize = false)
    {
        /** @var Article $entity */
        $entity = $this->repository->find($id);
        if(!$normalize){
            return $entity;
        }
        return $this->apiHelper->normalizeEntity($entity);
    }

    /**
     * Get a list of Articles.
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
     * Create a new Article.
     *
     * @param array $parameters
     *
     * @return Article
     */
    public function post(array $parameters)
    {
        $entity = $this->createArticle();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Article.
     *
     * @param Article $entity
     * @param array         $parameters
     *
     * @return Article
     */
    public function put(Article $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Article.
     *
     * @param Article $entity
     * @param array         $parameters
     *
     * @return Article
     */
    public function patch(Article $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Article.
     *
     * @param Article $entity
     *
     * @return Article
     */
    public function delete(Article $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param Article $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return Article
     *
     * @throws \Ojs\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(Article $entity, array $parameters, $method = "PUT")
    {
        $journal = $this->journalService->getSelectedJournal();
        $form = $this->formFactory->create(new ArticleType(), $entity, [
            'method' => $method,
            'csrf_protection' => false,
            'journal' => $journal,
        ]);

        $form->add('status', NumberType::class);
        $form->submit($parameters, 'PATCH' !== $method);
        $formData = $form->getData();

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
        $journalUploadDir = $rootDir . '/../web/uploads/journal/';
        if($isImage) {
            $fileHelper = new FileHelper();
            $generatePath = $fileHelper->generateRandomPath();
            if(!is_dir($journalUploadDir.$generatePath) || !is_dir($journalUploadDir.'croped/'.$generatePath)){
                mkdir($journalUploadDir.$generatePath, 0775, true);
                mkdir($journalUploadDir.'croped/'.$generatePath, 0775, true);
            }
            $filePath = $generatePath . $file['filename'];
            file_put_contents($journalUploadDir.$filePath, base64_decode($file['encoded_content']));
            file_put_contents($journalUploadDir.'croped/'.$filePath, base64_decode($file['encoded_content']));
            $this->apiHelper->createFileHistory($filePath, $filePath, 'journal', $this->om, true);
            return $filePath;
        }
    }

    private function createArticle()
    {
        return new $this->entityClass();
    }
}
