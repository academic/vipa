<?php

namespace Vipa\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Vipa\JournalBundle\Entity\Article;
use Vipa\JournalBundle\Entity\Citation;
use Vipa\JournalBundle\Form\Type\CitationType;
use Vipa\JournalBundle\Service\JournalService;
use Symfony\Component\Form\FormFactoryInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;
use Vipa\CoreBundle\Service\ApiHandlerHelper;
use Symfony\Component\HttpKernel\KernelInterface;
use \Doctrine\Common\Persistence\ObjectRepository;

class JournalArticleCitationHandler
{
    private $om;
    private $entityClass;
    /**
     * @var ObjectRepository
     */
    private $repository;
    private $formFactory;
    private $journalService;
    private $kernel;
    private $apiHelper;
    /**
     * @var  array
     */
    private $citationTypes;

    public function __construct(
        ObjectManager $om,
        $entityClass,
        FormFactoryInterface $formFactory,
        JournalService $journalService,
        KernelInterface $kernel,
        ApiHandlerHelper $apiHelper,
        $citationTypes
    )
    {
        $this->om               = $om;
        $this->entityClass      = $entityClass;
        $this->repository       = $this->om->getRepository($this->entityClass);
        $this->formFactory      = $formFactory;
        $this->journalService   = $journalService;
        $this->kernel           = $kernel;
        $this->apiHelper        = $apiHelper;
        $this->citationTypes    = $citationTypes;
    }

    /**
     * Get a Citation.
     *
     * @param mixed $id
     *
     * @return Citation
     */
    public function get($id)
    {
        /** @var Citation $entity */
        $entity = $this->repository->find($id);
        return $entity;
    }

    /**
     * Get a list of Citations.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->createQueryBuilder('c')
            ->join('c.articles', 'a')
            ->where('a.id = :articleId')
            ->setParameter('articleId', $this->getArticle()->getId())
            ->setFirstResult($offset)
            ->setMaxResults( $limit )
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Create a new Citation.
     *
     * @param array $parameters
     *
     * @return Citation
     */
    public function post(array $parameters)
    {
        $entity = $this->createCitation();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Citation.
     *
     * @param Citation $entity
     * @param array         $parameters
     *
     * @return Citation
     */
    public function put(Citation $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Citation.
     *
     * @param Citation $entity
     * @param array         $parameters
     *
     * @return Citation
     */
    public function patch(Citation $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Citation.
     *
     * @param Citation $entity
     *
     * @return Citation
     */
    public function delete(Citation $entity)
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
        $selectedArticle = $this->om->getRepository('VipaJournalBundle:Article')->findOneBy([
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
     * @param Citation $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return Citation
     *
     * @throws \Vipa\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(Citation $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new CitationType(), $entity, array(
            'method'            => $method,
            'csrf_protection'   => false,
            'citationTypes'     => array_keys($this->citationTypes),
        ));
        $form->submit($parameters, 'PATCH' !== $method);
        $formData = $form->getData();

        if ($form->isValid()) {
            $entity->addArticle($this->getArticle());
            $this->om->persist($entity);
            $this->om->flush();
            return $formData;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createCitation()
    {
        return new $this->entityClass();
    }
}