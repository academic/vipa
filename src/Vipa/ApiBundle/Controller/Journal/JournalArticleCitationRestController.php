<?php

namespace Vipa\ApiBundle\Controller\Journal;

use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Vipa\JournalBundle\Form\Type\CitationType;
use Vipa\JournalBundle\Entity\Citation;
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

class JournalArticleCitationRestController extends ApiController
{
    /**
     * List all ArticleCitations.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"articlecitation"},
     *   section = "articlecitation",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing ArticleCitations.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many ArticleCitations to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getCitationsAction(ParamFetcherInterface $paramFetcher)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('vipa_api.journal_article_citation.handler')->all($limit, $offset);
    }

    /**
     * Get single ArticleCitation.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a ArticleCitation for a given id",
     *   output = "Vipa\JournalBundle\Entity\ArticleCitation",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the ArticleCitation is not found"
     *   },
     *   views = {"articlecitation"},
     *   section = "articlecitation",
     * )
     *
     * @param int     $id      the ArticleCitation id
     *
     * @return array
     *
     * @throws NotFoundHttpException when ArticleCitation not exist
     */
    public function getCitationAction($id)
    {
        $entity = $this->getOr404($id);
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new ArticleCitation.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"articlecitation"},
     *   section = "articlecitation",
     * )
     *
     * @return FormTypeInterface
     */
    public function newCitationAction()
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new CitationType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a ArticleCitation from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new ArticleCitation from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"articlecitation"},
     *   section = "articlecitation",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postCitationAction(Request $request)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        try {
            $articleCitationHandler = $this->container->get('vipa_api.journal_article_citation.handler');
            $journalService = $this->container->get('vipa.journal_service');
            $newEntity = $articleCitationHandler->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                'articleId' => $articleCitationHandler->getArticle()->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_article_get_citation', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing ArticleCitation from the submitted data or create a new ArticleCitation at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the ArticleCitation is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"articlecitation"},
     *   section = "articlecitation",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the ArticleCitation id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when ArticleCitation not exist
     */
    public function putCitationAction(Request $request, $id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        try {
            $articleCitationHandler = $this->container->get('vipa_api.journal_article_citation.handler');
            $journalService = $this->container->get('vipa.journal_service');
            if (!($entity = $articleCitationHandler->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $articleCitationHandler->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $articleCitationHandler->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                'articleId' => $articleCitationHandler->getArticle()->getId(),
                '_format' => $request->get('_format'),
            );
            return $this->routeRedirectView('api_1_article_get_citation', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing article citation from the submitted data or create a new article citation at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"articlecitation"},
     *   section = "articlecitation",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the article citation id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when article citation not exist
     */
    public function patchCitationAction(Request $request, $id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        try {
            $articleCitationHandler = $this->container->get('vipa_api.journal_article_citation.handler');
            $journalService = $this->container->get('vipa.journal_service');
            $entity = $articleCitationHandler->patch(
                $this->getOr404($id),
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $entity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                'articleId' => $articleCitationHandler->getArticle()->getId(),
                '_format' => $request->get('_format'),
            );
            return $this->routeRedirectView('api_1_article_get_citation', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete ArticleCitation",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "ArticleCitation ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"articlecitation"},
     *      section = "articlecitation",
     * )
     *
     */
    public function deleteCitationAction($id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('DELETE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        $entity = $this->getOr404($id);
        $this->container->get('vipa_api.journal_article_citation.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a ArticleCitation or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return Citation
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('vipa_api.journal_article_citation.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
