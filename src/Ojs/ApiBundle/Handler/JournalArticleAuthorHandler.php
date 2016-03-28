<?php

namespace Ojs\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleAuthor;
use Ojs\JournalBundle\Form\Type\ArticleAuthorType;
use Ojs\JournalBundle\Service\JournalService;
use Symfony\Component\Form\FormFactoryInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;
use Symfony\Component\Filesystem\Filesystem;
use Ojs\CoreBundle\Service\ApiHandlerHelper;
use Symfony\Component\HttpKernel\KernelInterface;

class JournalArticleAuthorHandler
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
     * Get a ArticleAuthor.
     *
     * @param mixed $id
     *
     * @return ArticleAuthor
     */
    public function get($id)
    {
        /** @var ArticleAuthor $entity */
        $entity = $this->repository->find($id);
        return $this->apiHelper->normalizeEntity($entity);
    }

    /**
     * Get a list of ArticleAuthors.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy([
            'article' => $this->getArticle()
        ], null, $limit, $offset);
    }

    /**
     * Create a new ArticleAuthor.
     *
     * @param array $parameters
     *
     * @return ArticleAuthor
     */
    public function post(array $parameters)
    {
        $entity = $this->createArticleAuthor();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a ArticleAuthor.
     *
     * @param ArticleAuthor $entity
     * @param array         $parameters
     *
     * @return ArticleAuthor
     */
    public function put(ArticleAuthor $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a ArticleAuthor.
     *
     * @param ArticleAuthor $entity
     * @param array         $parameters
     *
     * @return ArticleAuthor
     */
    public function patch(ArticleAuthor $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a ArticleAuthor.
     *
     * @param ArticleAuthor $entity
     *
     * @return ArticleAuthor
     */
    public function delete(ArticleAuthor $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Get Article.
     *
     * @return Article
     */
    public function getArticle()
    {
        $request = $this->apiHelper->getRequestStack()->getCurrentRequest();
        if(!$request) {
            return false;
        }
        $articleId = $request->attributes->get('articleId');
        if (!$articleId) {
            return false;
        }
        /** @var Article $selectedArticle */
        $selectedArticle = $this->om->getRepository('OjsJournalBundle:Article')->findOneBy([
            'id' => $articleId,
            'journal' => $this->journalService->getSelectedJournal()
        ]);
        if (!$selectedArticle) {
            return false;
        }
        return $selectedArticle;
    }

    /**
     * Processes the form.
     *
     * @param ArticleAuthor $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return ArticleAuthor
     *
     * @throws \Ojs\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(ArticleAuthor $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new ArticleAuthorType(), $entity, array(
            'method' => $method,
            'csrf_protection' => false,
        ));
        $form->submit($parameters, 'PATCH' !== $method);
        $formData = $form->getData();

        if ($form->isValid()) {
            $entity->setArticle($this->getArticle());
            $this->om->persist($entity);
            $this->om->flush();
            return $formData;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function storeFile($file)
    {
        $rootDir = $this->kernel->getRootDir();
        $articleFileDir = $rootDir . '/../web/uploads/articlefiles/';

        $fs = new Filesystem();
        $fs->mkdir($articleFileDir);
        $fs->dumpFile($articleFileDir . $file['filename'], base64_decode($file['encoded_content']));
        return $file['filename'];
    }

    private function createArticleAuthor()
    {
        return new $this->entityClass();
    }
}