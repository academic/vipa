<?php

namespace Vipa\ApiBundle\Controller\Journal;

use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Vipa\JournalBundle\Form\Type\ArticleAuthorType;
use Vipa\JournalBundle\Entity\ArticleAuthor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;
use Vipa\ApiBundle\Controller\ApiController;

class JournalArticleAuthorRestController extends ApiController
{
    /**
     * List all ArticleAuthors.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"articleauthor"},
     *   section = "articleauthor",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing ArticleAuthors.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many ArticleAuthors to return.")
     *
     *
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getAuthorsAction(ParamFetcherInterface $paramFetcher)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        $articleAuthorHandler = $this->container->get('vipa_api.journal_article_author.handler');
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $articleAuthorHandler->all($limit, $offset);
    }

    /**
     * Get single ArticleAuthor.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a ArticleAuthor for a given id",
     *   output = "Vipa\JournalBundle\Entity\ArticleAuthor",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the ArticleAuthor is not found"
     *   },
     *   views = {"articleauthor"},
     *   section = "articleauthor",
     * )
     *
     * @param int     $id      the ArticleAuthor id
     *
     * @return array
     *
     * @throws NotFoundHttpException when ArticleAuthor not exist
     */
    public function getAuthorAction($id)
    {
        $entity = $this->getOr404($id);
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new ArticleAuthor.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"articleauthor"},
     *   section = "articleauthor",
     * )
     *
     * @return FormTypeInterface
     */
    public function newAuthorAction()
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new ArticleAuthorType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a ArticleAuthor from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new ArticleAuthor from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"articleauthor"},
     *   section = "articleauthor",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postAuthorAction(Request $request)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('vipa.journal_service');
            $newEntity = $this->container->get('vipa_api.journal_article_author.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                'articleId' => $newEntity->getArticle()->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_article_get_author', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing ArticleAuthor from the submitted data or create a new ArticleAuthor at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the ArticleAuthor is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"articleauthor"},
     *   section = "articleauthor",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the ArticleAuthor id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when ArticleAuthor not exist
     */
    public function putAuthorAction(Request $request, $id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('vipa.journal_service');
            if (!($entity = $this->container->get('vipa_api.journal_article_author.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('vipa_api.journal_article_author.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('vipa_api.journal_article_author.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                'articleId' => $entity->getArticle()->getId(),
                '_format' => $request->get('_format'),
            );
            return $this->routeRedirectView('api_1_article_get_author', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing article author from the submitted data or create a new article author at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"articleauthor"},
     *   section = "articleauthor",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the article author id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when article author not exist
     */
    public function patchAuthorAction(Request $request, $id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('vipa.journal_service');
            $entity = $this->container->get('vipa_api.journal_article_author.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $entity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                'articleId' => $entity->getArticle()->getId(),
                '_format' => $request->get('_format'),
            );
            return $this->routeRedirectView('api_1_article_get_author', $routeOptions, Codes::HTTP_NO_CONTENT);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @return Response
     * @ApiDoc(
     *      resource = false,
     *      description = "Delete ArticleAuthor",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "ArticleAuthor ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"articleauthor"},
     *      section = "articleauthor",
     * )
     *
     */
    public function deleteAuthorAction($id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('DELETE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        $entity = $this->getOr404($id);
        $this->container->get('vipa_api.journal_article_author.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a ArticleAuthor or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return ArticleAuthor
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('vipa_api.journal_article_author.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
