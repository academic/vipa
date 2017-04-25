<?php

namespace Vipa\ApiBundle\Controller\Journal;

use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Vipa\JournalBundle\Form\Type\ArticleType;
use Vipa\JournalBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;
use Vipa\ApiBundle\Controller\ApiController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class JournalArticleRestController extends ApiController
{
    /**
     * List all Articles.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"journalarticle"},
     *   section = "journalarticle",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing Articles.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many Articles to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getArticlesAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('vipa_api.journal_article.handler')->all($limit, $offset);
    }

    /**
     * Get single Article.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Article for a given id",
     *   output = "Vipa\ArticleBundle\Entity\Article",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the Article is not found"
     *   },
     *   views = {"journalarticle"},
     *   section = "journalarticle",
     * )
     *
     * @param int     $id      the Article id
     *
     * @return array
     *
     * @throws NotFoundHttpException when Article not exist
     */
    public function getArticleAction($id)
    {
        $entity = $this->getOr404($id, true);
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new Article.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"journalarticle"},
     *   section = "journalarticle",
     * )
     *
     * @return FormTypeInterface
     */
    public function newArticleAction()
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        
        $form = $this->createForm(new ArticleType(), null, [
                'csrf_protection' => false,
                'journal' => $journal,
            ]
        );
        $form->add('viewCount', NumberType::class, ['label' => 'articles.total.view']);
        $form->add('downloadCount', NumberType::class, ['label' => 'articles.total.download']);

        return $form;
    }

    /**
     * Create a Article from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Article from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"journalarticle"},
     *   section = "journalarticle",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postArticleAction(Request $request)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('vipa.journal_service');
            $newEntity = $this->container->get('vipa_api.journal_article.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_article', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing Article from the submitted data or create a new Article at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the Article is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"journalarticle"},
     *   section = "journalarticle",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the Article id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when Article not exist
     */
    public function putArticleAction(Request $request, $id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('vipa.journal_service');
            if (!($entity = $this->container->get('vipa_api.journal_article.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('vipa_api.journal_article.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('vipa_api.journal_article.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_article', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing journal_article from the submitted data or create a new journal_article at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"journalarticle"},
     *   section = "journalarticle",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the journal_article id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when journal_article not exist
     */
    public function patchArticleAction(Request $request, $id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('vipa.journal_service');
            $entity = $this->container->get('vipa_api.journal_article.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $entity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_article', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete Article",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "Article ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"journalarticle"},
     *      section = "journalarticle",
     * )
     *
     */
    public function deleteArticleAction($id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('DELETE', $journal, 'articles')) {
            throw new AccessDeniedException;
        }
        $entity = $this->getOr404($id);
        $this->container->get('vipa_api.journal_article.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a Article or throw an 404 Exception.
     *
     * @param mixed $id
     * @param bool $normalize
     *
     * @return Article
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id, $normalize = false)
    {
        if (!($entity = $this->container->get('vipa_api.journal_article.handler')->get($id, $normalize))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
