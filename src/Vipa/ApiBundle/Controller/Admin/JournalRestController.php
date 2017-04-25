<?php

namespace Vipa\ApiBundle\Controller\Admin;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Vipa\AdminBundle\Form\Type\JournalType;
use Vipa\JournalBundle\Entity\Journal;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class JournalRestController extends FOSRestController
{
    /**
     * List all Journals.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"journal"},
     *   section = "journal",
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
        if (!$this->isGranted('VIEW', new Journal())) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('vipa_api.journal.handler')->all($limit, $offset);
    }

    /**
     * Get single Journal.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Journal for a given id",
     *   output = "Vipa\JournalBundle\Entity\Journal",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the Journal is not found"
     *   },
     *   views = {"journal"},
     *   section = "journal",
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
        $entity = $this->getOr404($id, true);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new Journal.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"journal"},
     *   section = "journal",
     * )
     *
     * @return FormTypeInterface
     */
    public function newJournalAction()
    {
        if (!$this->isGranted('CREATE', new Journal())) {
            throw new AccessDeniedException;
        }
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
     *   },
     *   views = {"journal"},
     *   section = "journal",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postJournalAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new Journal())) {
            throw new AccessDeniedException;
        }
        try {
            $newEntity = $this->container->get('vipa_api.journal.handler')->post(
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
     *   },
     *   views = {"journal"},
     *   section = "journal",
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
        if (!$this->isGranted('CREATE', new Journal())) {
            throw new AccessDeniedException;
        }
        try {
            if (!($entity = $this->container->get('vipa_api.journal.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('vipa_api.journal.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('vipa_api.journal.handler')->put(
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
     *   },
     *   views = {"journal"},
     *   section = "journal",
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
            $entity = $this->container->get('vipa_api.journal.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );
            if (!$this->isGranted('EDIT', $entity)) {
                throw new AccessDeniedException;
            }
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
     *      },
     *      views = {"journal"},
     *      section = "journal",
     * )
     *
     */
    public function deleteJournalAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException;
        }
        $this->container->get('vipa_api.journal.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a Journal or throw an 404 Exception.
     *
     * @param mixed $id
     * @param bool $normalize
     *
     * @return Journal
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id, $normalize = false)
    {
        if (!($entity = $this->container->get('vipa_api.journal.handler')->get($id, $normalize))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
