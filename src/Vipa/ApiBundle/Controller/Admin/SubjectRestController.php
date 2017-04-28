<?php

namespace Vipa\ApiBundle\Controller\Admin;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Vipa\AdminBundle\Form\Type\SubjectType;
use Vipa\JournalBundle\Entity\Subject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class SubjectRestController extends FOSRestController
{
    /**
     * List all Subjects.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"subject"},
     *   section = "subject",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing Subjects.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many Subjects to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getSubjectsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        if (!$this->isGranted('VIEW', new Subject())) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('vipa_api.subject.handler')->all($limit, $offset);
    }

    /**
     * Get single Subject.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Subject for a given id",
     *   output = "Vipa\SubjectBundle\Entity\Subject",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the Subject is not found"
     *   },
     *   views = {"subject"},
     *   section = "subject",
     * )
     *
     * @param int     $id      the Subject id
     *
     * @return array
     *
     * @throws NotFoundHttpException when Subject not exist
     */
    public function getSubjectAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new Subject.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"subject"},
     *   section = "subject",
     * )
     *
     * @return FormTypeInterface
     */
    public function newSubjectAction()
    {
        if (!$this->isGranted('CREATE', new Subject())) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new SubjectType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a Subject from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Subject from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"subject"},
     *   section = "subject",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postSubjectAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new Subject())) {
            throw new AccessDeniedException;
        }
        try {
            $newEntity = $this->container->get('vipa_api.subject.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_subjects', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing Subject from the submitted data or create a new Subject at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the Subject is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"subject"},
     *   section = "subject",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the Subject id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when Subject not exist
     */
    public function putSubjectAction(Request $request, $id)
    {
        if (!$this->isGranted('CREATE', new Subject())) {
            throw new AccessDeniedException;
        }
        try {
            if (!($entity = $this->container->get('vipa_api.subject.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('vipa_api.subject.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('vipa_api.subject.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_subject', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing subject from the submitted data or create a new subject at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"subject"},
     *   section = "subject",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the subject id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when subject not exist
     */
    public function patchSubjectAction(Request $request, $id)
    {
        try {
            $entity = $this->container->get('vipa_api.subject.handler')->patch(
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
            return $this->routeRedirectView('api_1_get_subject', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete Subject",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "Subject ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"subject"},
     *      section = "subject",
     * )
     *
     */
    public function deleteSubjectAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException;
        }
        $this->container->get('vipa_api.subject.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a Subject or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return Subject
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('vipa_api.subject.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
