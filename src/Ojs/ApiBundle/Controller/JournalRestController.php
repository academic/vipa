<?php

namespace Ojs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\AdminBundle\Form\Type\JournalType;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;

class JournalRestController extends FOSRestController
{
    /**
     * List all Journals.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing Journals.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many Journals to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getJournalsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('ojs_api.journal.handler')->all($limit, $offset);
    }

    /**
     * Get single Journal.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Journal for a given id",
     *   output = "Ojs\JournalBundle\Entity\Journal",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the Journal is not found"
     *   }
     * )
     *
     * @param int     $id      the Journal id
     *
     * @return array
     *
     * @throws NotFoundHttpException when Journal not exist
     */
    public function getJournalAction($id)
    {
        $entity = $this->getOr404($id);
        return $entity;
    }

    /**
     * Presents the form to use to create a new Journal.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @return FormTypeInterface
     */
    public function newJournalAction()
    {
        return $this->createForm(new JournalType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a Journal from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Journal from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postJournalAction(Request $request)
    {
        try {
            $newEntity = $this->container->get('ojs_api.journal.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_journals', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing Journal from the submitted data or create a new Journal at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the Journal is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the Journal id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when Journal not exist
     */
    public function putJournalAction(Request $request, $id)
    {
        try {
            if (!($entity = $this->container->get('ojs_api.journal.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('ojs_api.journal.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('ojs_api.journal.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_journal', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing journal from the submitted data or create a new journal at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the journal id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when journal not exist
     */
    public function patchJournalAction(Request $request, $id)
    {
        try {
            $entity = $this->container->get('ojs_api.journal.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $entity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_journal', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete Journal",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "Journal ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      }
     * )
     *
     */
    public function deleteJournalAction($id)
    {
        $entity = $this->getOr404($id);
        $this->container->get('ojs_api.journal.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a Journal or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return Journal
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('ojs_api.journal.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Journal Issues"
     * )
     * @Get("/journal/{id}/issues")
     *
     */
    public function getJournalIssuesAction($id)
    {
        $journal = $this->getDoctrine()->getRepository('OjsJournalBundle:Journal')->find($id);
        if (!is_object($journal)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $journal->getIssues();
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Specific Journal Of Users Action",
     *  parameters={
     *      {
     *          "name"="page",
     *          "dataType"="integer",
     *          "required"="true",
     *          "description"="offset page"
     *      },
     *      {
     *          "name"="limit",
     *          "dataType"="integer",
     *          "required"="true",
     *          "description"="limit"
     *      }
     *  }
     * )
     * @Get("/journal/{id}/users")
     *
     * @param  Request $request
     * @param $id
     * @return mixed
     */
    public function getJournalUsersAction(Request $request, $id)
    {
        $limit = $request->get('limit');
        $page = (int) $request->get('page'); // page is not a mandotary parameter
        if (empty($limit)) {
            throw new HttpException(400, 'Missing parameter : limit');
        }

        return $this->get('ojs.journal_service')->getUsers($id, $page, $limit);
    }
}
