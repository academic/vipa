<?php
namespace Ojs\ApiBundle\Controller\Journal;

use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\JournalBundle\Form\Type\IssueFileType;
use Ojs\JournalBundle\Entity\IssueFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;
use Ojs\ApiBundle\Controller\ApiController;

class JournalIssueFileRestController extends ApiController
{
    /**
     * List all IssueFiles.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"issuefile"},
     *   section = "issuefile",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing IssueFiles.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many IssueFiles to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getFilesAction(ParamFetcherInterface $paramFetcher)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'issues')) {
            throw new AccessDeniedException;
        }
        $issueFileHandler = $this->container->get('ojs_api.journal_issue_file.handler');
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $issueFileHandler->all($limit, $offset);
    }

    /**
     * Get single IssueFile.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a IssueFile for a given id",
     *   output = "Ojs\JournalBundle\Entity\IssueFile",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the IssueFile is not found"
     *   },
     *   views = {"issuefile"},
     *   section = "issuefile",
     * )
     *
     * @param int     $id      the IssueFile id
     *
     * @return array
     *
     * @throws NotFoundHttpException when IssueFile not exist
     */
    public function getFileAction($id)
    {
        $entity = $this->getOr404($id, true);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'issues')) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new IssueFile.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"issuefile"},
     *   section = "issuefile",
     * )
     *
     * @return FormTypeInterface
     */
    public function newFileAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'issues')) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new IssueFileType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a IssueFile from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new IssueFile from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"issuefile"},
     *   section = "issuefile",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postFileAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'issues')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('ojs.journal_service');
            $newEntity = $this->container->get('ojs_api.journal_issue_file.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                'issueId' => $newEntity->getIssue()->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_issue_get_file', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing IssueFile from the submitted data or create a new IssueFile at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the IssueFile is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"issuefile"},
     *   section = "issuefile",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the IssueFile id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when IssueFile not exist
     */
    public function putFileAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'issues')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('ojs.journal_service');
            if (!($entity = $this->container->get('ojs_api.journal_issue_file.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('ojs_api.journal_issue_file.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('ojs_api.journal_issue_file.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                'issueId' => $entity->getIssue()->getId(),
                '_format' => $request->get('_format'),
            );
            return $this->routeRedirectView('api_1_issue_get_file', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing issue file from the submitted data or create a new issue file at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"issuefie"},
     *   section = "issuefie",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the issue file id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when issue file not exist
     */
    public function patchFileAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'issues')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('ojs.journal_service');
            $entity = $this->container->get('ojs_api.journal_issue_file.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $entity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                'issueId' => $entity->getIssue()->getId(),
                '_format' => $request->get('_format'),
            );
            return $this->routeRedirectView('api_1_issue_get_file', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete IssueFile",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "IssueFile ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"issuefile"},
     *      section = "issuefile",
     * )
     *
     */
    public function deleteFileAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('DELETE', $journal, 'issues')) {
            throw new AccessDeniedException;
        }
        $entity = $this->getOr404($id);
        $this->container->get('ojs_api.journal_issue_file.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a IssueFile or throw an 404 Exception.
     *
     * @param mixed $id
     * @param bool $normalize
     *
     * @return IssueFile
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id, $normalize = false)
    {
        if (!($entity = $this->container->get('ojs_api.journal_issue_file.handler')->get($id, $normalize))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }

}
