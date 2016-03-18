<?php

namespace Ojs\ApiBundle\Controller\Admin;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\AdminBundle\Form\Type\PublisherThemeType;
use Ojs\JournalBundle\Entity\PublisherTheme;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;

class PublisherThemeRestController extends FOSRestController
{
    /**
     * List all PublisherTheme.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"publishertheme"},
     *   section = "publishertheme",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing PublisherTheme.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many PublisherTheme to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getPublisherthemesAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        if (!$this->isGranted('VIEW', new PublisherTheme())) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('ojs_api.publisher_theme.handler')->all($limit, $offset);
    }

    /**
     * Get single PublisherTheme.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a PublisherTheme for a given id",
     *   output = "Ojs\PublisherThemeBundle\Entity\PublisherTheme",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the PublisherTheme is not found"
     *   },
     *   views = {"publishertheme"},
     *   section = "publishertheme",
     * )
     *
     * @param int     $id      the PublisherTheme id
     *
     * @return array
     *
     * @throws NotFoundHttpException when PublisherTheme not exist
     */
    public function getPublisherthemeAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new PublisherTheme.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"publishertheme"},
     *   section = "publishertheme",
     * )
     *
     * @return FormTypeInterface
     */
    public function newPublisherthemeAction()
    {
        if (!$this->isGranted('CREATE', new PublisherTheme())) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new PublisherThemeType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a PublisherTheme from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new PublisherTheme from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"publishertheme"},
     *   section = "publishertheme",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postPublisherthemeAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new PublisherTheme())) {
            throw new AccessDeniedException;
        }
        try {
            $newEntity = $this->container->get('ojs_api.publisher_theme.handler')->post(
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
     * Update existing PublisherTheme from the submitted data or create a new PublisherTheme at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the PublisherTheme is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"publishertheme"},
     *   section = "publishertheme",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the PublisherTheme id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when PublisherTheme not exist
     */
    public function putPublisherthemeAction(Request $request, $id)
    {
        if (!$this->isGranted('CREATE', new PublisherTheme())) {
            throw new AccessDeniedException;
        }
        try {
            if (!($entity = $this->container->get('ojs_api.publisher_theme.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('ojs_api.publisher_theme.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('ojs_api.publisher_theme.handler')->put(
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
     * Update existing publisher_theme from the submitted data or create a new publisher_theme at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"publishertheme"},
     *   section = "publishertheme",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the publisher_theme id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when publisher_theme not exist
     */
    public function patchPublisherthemeAction(Request $request, $id)
    {
        try {
            $entity = $this->container->get('ojs_api.publisher_theme.handler')->patch(
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
     *      description = "Delete PublisherTheme",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "PublisherTheme ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"publishertheme"},
     *      section = "publishertheme",
     * )
     *
     */
    public function deletePublisherthemeAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException;
        }
        $this->container->get('ojs_api.publisher_theme.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a PublisherTheme or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return PublisherTheme
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('ojs_api.publisher_theme.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
