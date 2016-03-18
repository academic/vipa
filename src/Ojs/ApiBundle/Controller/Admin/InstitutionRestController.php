<?php

namespace Ojs\ApiBundle\Controller\Admin;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\AdminBundle\Form\Type\InstitutionType;
use Ojs\JournalBundle\Entity\Institution;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;

class InstitutionRestController extends FOSRestController
{
    /**
     * List all Institutions.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"institution"},
     *   section = "institution",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing Institutions.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many Institutions to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getInstitutionsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        if (!$this->isGranted('VIEW', new Institution())) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('ojs_api.institution.handler')->all($limit, $offset);
    }

    /**
     * Get single Institution.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Institution for a given id",
     *   output = "Ojs\InstitutionBundle\Entity\Institution",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the Institution is not found"
     *   },
     *   views = {"institution"},
     *   section = "institution",
     * )
     *
     * @param int     $id      the Institution id
     *
     * @return array
     *
     * @throws NotFoundHttpException when Institution not exist
     */
    public function getInstitutionAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new Institution.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"institution"},
     *   section = "institution",
     * )
     *
     * @return FormTypeInterface
     */
    public function newInstitutionAction()
    {
        if (!$this->isGranted('CREATE', new Institution())) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new InstitutionType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a Institution from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Institution from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"institution"},
     *   section = "institution",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postInstitutionAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new Institution())) {
            throw new AccessDeniedException;
        }
        try {
            $newEntity = $this->container->get('ojs_api.institution.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_institutions', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing Institution from the submitted data or create a new Institution at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the Institution is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"institution"},
     *   section = "institution",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the Institution id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when Institution not exist
     */
    public function putInstitutionAction(Request $request, $id)
    {
        if (!$this->isGranted('CREATE', new Institution())) {
            throw new AccessDeniedException;
        }
        try {
            if (!($entity = $this->container->get('ojs_api.institution.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('ojs_api.institution.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('ojs_api.institution.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_institution', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing institution from the submitted data or create a new institution at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"institution"},
     *   section = "institution",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the institution id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when institution not exist
     */
    public function patchInstitutionAction(Request $request, $id)
    {
        try {
            $entity = $this->container->get('ojs_api.institution.handler')->patch(
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
            return $this->routeRedirectView('api_1_get_institution', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete Institution",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "Institution ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"institution"},
     *      section = "institution",
     * )
     *
     */
    public function deleteInstitutionAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException;
        }
        $this->container->get('ojs_api.institution.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a Institution or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return Institution
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('ojs_api.institution.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
