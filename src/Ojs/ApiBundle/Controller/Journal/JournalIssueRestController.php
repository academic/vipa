<?php

namespace Ojs\ApiBundle\Controller\Journal;

use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\JournalBundle\Form\Type\IssueType;
use Ojs\JournalBundle\Entity\Issue;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;
use Ojs\ApiBundle\Controller\ApiController;

class JournalIssueRestController extends ApiController
{
    /**
     * List all Issues.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"journalissue"},
     *   section = "journalissue",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing Issues.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many Issues to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getIssuesAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'issues')) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('ojs_api.journal_issue.handler')->all($limit, $offset);
    }

    /**
     * Get single Issue.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Issue for a given id",
     *   output = "Ojs\IssueBundle\Entity\Issue",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the Issue is not found"
     *   },
     *   views = {"journalissue"},
     *   section = "journalissue",
     * )
     *
     * @param int     $id      the Issue id
     *
     * @return array
     *
     * @throws NotFoundHttpException when Issue not exist
     */
    public function getIssueAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'issues')) {
            throw new AccessDeniedException;
        }
        $entity = $this->getOr404($id);
        return $entity;
    }

    /**
     * Presents the form to use to create a new Issue.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"journalissue"},
     *   section = "journalissue",
     * )
     *
     * @return FormTypeInterface
     */
    public function newIssueAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'issues')) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new IssueType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a Issue from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Issue from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"journalissue"},
     *   section = "journalissue",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postIssueAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'issues')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('ojs.journal_service');
            $newEntity = $this->container->get('ojs_api.journal_issue.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_issues', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing Issue from the submitted data or create a new Issue at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the Issue is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"journalissue"},
     *   section = "journalissue",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the Issue id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when Issue not exist
     */
    public function putIssueAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'issues')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('ojs.journal_service');
            if (!($entity = $this->container->get('ojs_api.journal_issue.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('ojs_api.journal_issue.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('ojs_api.journal_issue.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_issue', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing journal_issue from the submitted data or create a new journal_issue at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"journalissue"},
     *   section = "journalissue",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the journal_issue id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when journal_issue not exist
     */
    public function patchIssueAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'issues')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('ojs.journal_service');
            $entity = $this->container->get('ojs_api.journal_issue.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $entity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_issue', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete Issue",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "Issue ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"journalissue"},
     *      section = "journalissue",
     * )
     *
     */
    public function deleteIssueAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('DELETE', $journal, 'issues')) {
            throw new AccessDeniedException;
        }
        $entity = $this->getOr404($id);
        $this->container->get('ojs_api.journal_issue.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a Issue or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return Issue
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('ojs_api.journal_issue.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
