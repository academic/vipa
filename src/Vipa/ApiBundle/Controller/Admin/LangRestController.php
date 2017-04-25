<?php

namespace Vipa\ApiBundle\Controller\Admin;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Vipa\AdminBundle\Form\Type\LangType;
use Vipa\JournalBundle\Entity\Lang;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class LangRestController extends FOSRestController
{
    /**
     * List all Langs.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"lang"},
     *   section = "lang",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing Langs.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many Langs to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getLangsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        if (!$this->isGranted('VIEW', new Lang())) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('vipa_api.lang.handler')->all($limit, $offset);
    }

    /**
     * Get single Lang.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Lang for a given id",
     *   output = "Vipa\LangBundle\Entity\Lang",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the Lang is not found"
     *   },
     *   views = {"lang"},
     *   section = "lang",
     * )
     *
     * @param int     $id      the Lang id
     *
     * @return array
     *
     * @throws NotFoundHttpException when Lang not exist
     */
    public function getLangAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new Lang.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"lang"},
     *   section = "lang",
     * )
     *
     * @return FormTypeInterface
     */
    public function newLangAction()
    {
        if (!$this->isGranted('CREATE', new Lang())) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new LangType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a Lang from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Lang from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"lang"},
     *   section = "lang",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postLangAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new Lang())) {
            throw new AccessDeniedException;
        }
        try {
            $newEntity = $this->container->get('vipa_api.lang.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_langs', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing Lang from the submitted data or create a new Lang at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the Lang is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"lang"},
     *   section = "lang",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the Lang id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when Lang not exist
     */
    public function putLangAction(Request $request, $id)
    {
        if (!$this->isGranted('CREATE', new Lang())) {
            throw new AccessDeniedException;
        }
        try {
            if (!($entity = $this->container->get('vipa_api.lang.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('vipa_api.lang.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('vipa_api.lang.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_lang', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing lang from the submitted data or create a new lang at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"lang"},
     *   section = "lang",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the lang id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when lang not exist
     */
    public function patchLangAction(Request $request, $id)
    {
        try {
            $entity = $this->container->get('vipa_api.lang.handler')->patch(
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
            return $this->routeRedirectView('api_1_get_lang', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete Lang",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "Lang ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"lang"},
     *      section = "lang",
     * )
     *
     */
    public function deleteLangAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException;
        }
        $this->container->get('vipa_api.lang.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a Lang or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return Lang
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('vipa_api.lang.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
