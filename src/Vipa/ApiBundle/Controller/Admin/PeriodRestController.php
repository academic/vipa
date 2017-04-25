<?php

namespace Vipa\ApiBundle\Controller\Admin;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Vipa\AdminBundle\Form\Type\PeriodType;
use Vipa\JournalBundle\Entity\Period;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class PeriodRestController extends FOSRestController
{
    /**
     * List all Periods.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"period"},
     *   section = "period",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing Periods.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many Periods to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getPeriodsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        if (!$this->isGranted('VIEW', new Period())) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('vipa_api.period.handler')->all($limit, $offset);
    }

    /**
     * Get single Period.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Period for a given id",
     *   output = "Vipa\PeriodBundle\Entity\Period",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the Period is not found"
     *   },
     *   views = {"period"},
     *   section = "period",
     * )
     *
     * @param int     $id      the Period id
     *
     * @return array
     *
     * @throws NotFoundHttpException when Period not exist
     */
    public function getPeriodAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new Period.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"period"},
     *   section = "period",
     * )
     *
     * @return FormTypeInterface
     */
    public function newPeriodAction()
    {
        if (!$this->isGranted('CREATE', new Period())) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new PeriodType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a Period from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Period from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"period"},
     *   section = "period",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postPeriodAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new Period())) {
            throw new AccessDeniedException;
        }
        try {
            $newEntity = $this->container->get('vipa_api.period.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_periods', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing Period from the submitted data or create a new Period at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the Period is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"period"},
     *   section = "period",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the Period id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when Period not exist
     */
    public function putPeriodAction(Request $request, $id)
    {
        if (!$this->isGranted('CREATE', new Period())) {
            throw new AccessDeniedException;
        }
        try {
            if (!($entity = $this->container->get('vipa_api.period.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('vipa_api.period.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('vipa_api.period.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_period', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing period from the submitted data or create a new period at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"period"},
     *   section = "period",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the period id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when period not exist
     */
    public function patchPeriodAction(Request $request, $id)
    {
        try {
            $entity = $this->container->get('vipa_api.period.handler')->patch(
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
            return $this->routeRedirectView('api_1_get_period', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete Period",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "Period ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"period"},
     *      section = "period",
     * )
     *
     */
    public function deletePeriodAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException;
        }
        $this->container->get('vipa_api.period.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a Period or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return Period
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('vipa_api.period.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
