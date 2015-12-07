<?php

namespace Ojs\ApiBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\ApiBundle\Exception\InvalidFormException;
use Ojs\AdminBundle\Form\Type\ContactTypesType;
use Ojs\ApiBundle\Model\ContactTypesInterface;

class ContactTypesRestController extends FOSRestController
{
    /**
     * List all contact types.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing contact types.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many contact types to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getContacttypesAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('ojs_api.contact_type.handler')->all($limit, $offset);
    }

    /**
     * Get single Contact Type.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Contact Type for a given id",
     *   output = "Ojs\JournalBundle\Entity\ContactType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the contact type is not found"
     *   }
     * )
     *
     * @param int     $id      the contact type id
     *
     * @return array
     *
     * @throws NotFoundHttpException when contact type not exist
     */
    public function getContacttypeAction($id)
    {
        $contactType = $this->getOr404($id);
        return $contactType;
    }

    /**
     * Presents the form to use to create a new contact type.
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
    public function newContacttypeAction()
    {
        return $this->createForm(new ContactTypesType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a Contact Type from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Contact Type from the submitted data.",
     *   input = "Ojs\JournalBundle\Form\Type\ContactTypesType",
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
    public function postContacttypeAction(Request $request)
    {
        try {
            $newContactType = $this->container->get('ojs_api.contact_type.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newContactType->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_contacttypes', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing contact type from the submitted data or create a new contact type at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ojs\JournalBundle\Form\Type\ContactTypesType",
     *   statusCodes = {
     *     201 = "Returned when the ContactType is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the contact type id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when contact type not exist
     */
    public function putContacttypeAction(Request $request, $id)
    {
        try {
            if (!($contactType = $this->container->get('ojs_api.contact_type.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $contactType = $this->container->get('ojs_api.contact_type.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $contactType = $this->container->get('ojs_api.contact_type.handler')->put(
                    $contactType,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $contactType->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_contacttype', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing contact type from the submitted data or create a new contact type at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ojs\JournalBundle\Form\Type\ContactTypesType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the contact type id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when contact type not exist
     */
    public function patchContacttypeAction(Request $request, $id)
    {
        try {
            $contactType = $this->container->get('ojs_api.contact_type.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $contactType->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_contacttype', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete Contact Type",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "Contact Type ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      }
     * )
     *
     */
    public function deleteContacttypeAction($id)
    {
        $entity = $this->getOr404($id);
        $this->container->get('ojs_api.contact_type.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a Contact Type or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return ContactTypesInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($contactType = $this->container->get('ojs_api.contact_type.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $contactType;
    }
}
