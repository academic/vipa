<?php

namespace Ojs\ApiBundle\Controller\Admin;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\AdminBundle\Form\Type\PublisherManagersType;
use Ojs\AdminBundle\Entity\PublisherManagers;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;

class PublisherManagerRestController extends FOSRestController
{
    /**
     * List all PublisherManager.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"publishermanager"},
     *   section = "publishermanager",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing PublisherManager.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many PublisherManager to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getPublishermanagersAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        if (!$this->isGranted('VIEW', new PublisherManagers())) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('ojs_api.publisher_manager.handler')->all($limit, $offset);
    }

    /**
     * Get single PublisherManager.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a PublisherManager for a given id",
     *   output = "Ojs\PublisherManagerBundle\Entity\PublisherManager",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the PublisherManager is not found"
     *   },
     *   views = {"publishermanager"},
     *   section = "publishermanager",
     * )
     *
     * @param int     $id      the PublisherManager id
     *
     * @return array
     *
     * @throws NotFoundHttpException when PublisherManager not exist
     */
    public function getPublishermanagerAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new PublisherManager.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"publishermanager"},
     *   section = "publishermanager",
     * )
     *
     * @return FormTypeInterface
     */
    public function newPublishermanagerAction()
    {
        if (!$this->isGranted('CREATE', new PublisherManagers())) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new PublisherManagersType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a PublisherManager from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new PublisherManager from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"publishermanager"},
     *   section = "publishermanager",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postPublishermanagerAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new PublisherManagers())) {
            throw new AccessDeniedException;
        }
        try {
            $newEntity = $this->container->get('ojs_api.publisher_manager.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_publishermanager', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing PublisherManager from the submitted data or create a new PublisherManager at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the PublisherManager is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"publishermanager"},
     *   section = "publishermanager",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the PublisherManager id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when PublisherManager not exist
     */
    public function putPublishermanagerAction(Request $request, $id)
    {
        if (!$this->isGranted('CREATE', new PublisherManagers())) {
            throw new AccessDeniedException;
        }
        try {
            if (!($entity = $this->container->get('ojs_api.publisher_manager.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('ojs_api.publisher_manager.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('ojs_api.publisher_manager.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_publishermanager', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing publisher_manager from the submitted data or create a new publisher_manager at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"publishermanager"},
     *   section = "publishermanager",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the publisher_manager id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when publisher_manager not exist
     */
    public function patchPublishermanagerAction(Request $request, $id)
    {
        try {
            $entity = $this->container->get('ojs_api.publisher_manager.handler')->patch(
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
            return $this->routeRedirectView('api_1_get_publishermanager', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete PublisherManager",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "PublisherManager ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"publishermanager"},
     *      section = "publishermanager",
     * )
     *
     */
    public function deletePublishermanagerAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException;
        }
        $this->container->get('ojs_api.publisher_manager.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a PublisherManager or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return PublisherManagers
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('ojs_api.publisher_manager.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
