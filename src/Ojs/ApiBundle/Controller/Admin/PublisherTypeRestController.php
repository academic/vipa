<?php

namespace Ojs\ApiBundle\Controller\Admin;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\AdminBundle\Form\Type\PublisherTypesType;
use Ojs\JournalBundle\Entity\PublisherTypes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;

class PublisherTypeRestController extends FOSRestController
{
    /**
     * List all PublisherTypes.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"publishertype"},
     *   section = "publishertype",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing PublisherTypes.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many PublisherTypes to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getPublishertypesAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        if (!$this->isGranted('VIEW', new PublisherTypes())) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('ojs_api.publisher_type.handler')->all($limit, $offset);
    }

    /**
     * Get single PublisherType.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a PublisherType for a given id",
     *   output = "Ojs\PublisherTypeBundle\Entity\PublisherType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the PublisherType is not found"
     *   },
     *   views = {"publishertype"},
     *   section = "publishertype",
     * )
     *
     * @param int     $id      the PublisherType id
     *
     * @return array
     *
     * @throws NotFoundHttpException when PublisherType not exist
     */
    public function getPublishertypeAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new PublisherType.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"publishertype"},
     *   section = "publishertype",
     * )
     *
     * @return FormTypeInterface
     */
    public function newPublishertypeAction()
    {
        if (!$this->isGranted('CREATE', new PublisherTypes())) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new PublisherTypesType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a PublisherType from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new PublisherType from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"publishertype"},
     *   section = "publishertype",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postPublishertypeAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new PublisherTypes())) {
            throw new AccessDeniedException;
        }
        try {
            $newEntity = $this->container->get('ojs_api.publisher_type.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_persontitle', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing PublisherType from the submitted data or create a new PublisherType at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the PublisherType is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"publishertype"},
     *   section = "publishertype",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the PublisherType id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when PublisherType not exist
     */
    public function putPublishertypeAction(Request $request, $id)
    {
        if (!$this->isGranted('CREATE', new PublisherTypes())) {
            throw new AccessDeniedException;
        }
        try {
            if (!($entity = $this->container->get('ojs_api.publisher_type.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('ojs_api.publisher_type.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('ojs_api.publisher_type.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_persontitle', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing publisherType from the submitted data or create a new publisherType at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"publishertype"},
     *   section = "publishertype",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the publisherType id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when publisherType not exist
     */
    public function patchPublishertypeAction(Request $request, $id)
    {
        try {
            $entity = $this->container->get('ojs_api.publisher_type.handler')->patch(
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
            return $this->routeRedirectView('api_1_get_persontitle', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete PublisherType",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "PublisherType ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"publishertype"},
     *      section = "publishertype",
     * )
     *
     */
    public function deletePublishertypeAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException;
        }
        $this->container->get('ojs_api.publisher_type.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a PublisherType or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return PublisherTypes
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('ojs_api.publisher_type.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
