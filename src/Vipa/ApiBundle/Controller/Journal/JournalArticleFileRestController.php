<?php

namespace Vipa\ApiBundle\Controller\Journal;

use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Vipa\JournalBundle\Form\Type\ArticleFileType;
use Vipa\JournalBundle\Entity\ArticleFile;
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

class JournalArticleFileRestController extends ApiController
{
    /**
     * List all ArticleFiles.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"articlefile"},
     *   section = "articlefile",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing ArticleFiles.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many ArticleFiles to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getFilesAction(ParamFetcherInterface $paramFetcher)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        $articleFileHandler = $this->container->get('vipa_api.journal_article_file.handler');
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $articleFileHandler->all($limit, $offset);
    }

    /**
     * Get single ArticleFile.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a ArticleFile for a given id",
     *   output = "Vipa\JournalBundle\Entity\ArticleFile",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the ArticleFile is not found"
     *   },
     *   views = {"articlefile"},
     *   section = "articlefile",
     * )
     *
     * @param int     $id      the ArticleFile id
     *
     * @return array
     *
     * @throws NotFoundHttpException when ArticleFile not exist
     */
    public function getFileAction($id)
    {
        $entity = $this->getOr404($id, true);
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new ArticleFile.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"articlefile"},
     *   section = "articlefile",
     * )
     *
     * @return FormTypeInterface
     */
    public function newFileAction()
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new ArticleFileType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a ArticleFile from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new ArticleFile from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"articlefile"},
     *   section = "articlefile",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postFileAction(Request $request)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('vipa.journal_service');
            $newEntity = $this->container->get('vipa_api.journal_article_file.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                'articleId' => $newEntity->getArticle()->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_article_get_file', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing ArticleFile from the submitted data or create a new ArticleFile at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the ArticleFile is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"articlefile"},
     *   section = "articlefile",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the ArticleFile id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when ArticleFile not exist
     */
    public function putFileAction(Request $request, $id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('vipa.journal_service');
            if (!($entity = $this->container->get('vipa_api.journal_article_file.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('vipa_api.journal_article_file.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('vipa_api.journal_article_file.handler')->put(
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
            return $this->routeRedirectView('api_1_article_get_file', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing article file from the submitted data or create a new article file at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"articlefile"},
     *   section = "articlefile",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the article file id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when article file not exist
     */
    public function patchFileAction(Request $request, $id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('vipa.journal_service');
            $entity = $this->container->get('vipa_api.journal_article_file.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $entity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                'articleId' => $entity->getArticle()->getId(),
                '_format' => $request->get('_format'),
            );
            return $this->routeRedirectView('api_1_article_get_file', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete ArticleFile",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "ArticleFile ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"articlefile"},
     *      section = "articlefile",
     * )
     *
     */
    public function deleteFileAction($id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('DELETE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        $entity = $this->getOr404($id);
        $this->container->get('vipa_api.journal_article_file.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a ArticleFile or throw an 404 Exception.
     *
     * @param mixed $id
     * @param bool $normalize
     *
     * @return ArticleFile
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id, $normalize = false)
    {
        if (!($entity = $this->container->get('vipa_api.journal_article_file.handler')->get($id, $normalize))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
