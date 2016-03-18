<?php

namespace Ojs\ApiBundle\Controller\Admin;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\AdminBundle\Form\Type\PersonTitleType;
use Ojs\JournalBundle\Entity\PersonTitle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;

class PersonTitleRestController extends FOSRestController
{
    /**
     * List all PersonTitles.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"persontitle"},
     *   section = "persontitle",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing PersonTitles.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many PersonTitles to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getPersontitlesAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        if (!$this->isGranted('VIEW', new PersonTitle())) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('ojs_api.person_title.handler')->all($limit, $offset);
    }

    /**
     * Get single PersonTitle.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a PersonTitle for a given id",
     *   output = "Ojs\PersonTitleBundle\Entity\PersonTitle",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the PersonTitle is not found"
     *   },
     *   views = {"persontitle"},
     *   section = "persontitle",
     * )
     *
     * @param int     $id      the PersonTitle id
     *
     * @return array
     *
     * @throws NotFoundHttpException when PersonTitle not exist
     */
    public function getPersontitleAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new PersonTitle.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"persontitle"},
     *   section = "persontitle",
     * )
     *
     * @return FormTypeInterface
     */
    public function newPersontitleAction()
    {
        if (!$this->isGranted('CREATE', new PersonTitle())) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new PersonTitleType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a PersonTitle from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new PersonTitle from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"persontitle"},
     *   section = "persontitle",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postPersontitleAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new PersonTitle())) {
            throw new AccessDeniedException;
        }
        try {
            $newEntity = $this->container->get('ojs_api.person_title.handler')->post(
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
     * Update existing PersonTitle from the submitted data or create a new PersonTitle at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the PersonTitle is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"persontitle"},
     *   section = "persontitle",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the PersonTitle id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when PersonTitle not exist
     */
    public function putPersontitleAction(Request $request, $id)
    {
        if (!$this->isGranted('CREATE', new PersonTitle())) {
            throw new AccessDeniedException;
        }
        try {
            if (!($entity = $this->container->get('ojs_api.person_title.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('ojs_api.person_title.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('ojs_api.person_title.handler')->put(
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
     * Update existing person_title from the submitted data or create a new person_title at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"persontitle"},
     *   section = "persontitle",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the person_title id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when person_title not exist
     */
    public function patchPersontitleAction(Request $request, $id)
    {
        try {
            $entity = $this->container->get('ojs_api.person_title.handler')->patch(
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
     *      description = "Delete PersonTitle",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "PersonTitle ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"persontitle"},
     *      section = "persontitle",
     * )
     *
     */
    public function deletePersontitleAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException;
        }
        $this->container->get('ojs_api.person_title.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a PersonTitle or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return PersonTitle
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('ojs_api.person_title.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
