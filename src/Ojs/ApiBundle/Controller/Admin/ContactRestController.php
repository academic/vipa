<?php

namespace Ojs\ApiBundle\Controller\Admin;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\View;
use Ojs\AdminBundle\Form\Type\ContactType;
use Ojs\JournalBundle\Entity\JournalContact;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;

class ContactRestController extends FOSRestController
{
    /**
     * List all Contacts.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"contact"},
     *   section = "contact",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing Contacts.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many Contacts to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getContactsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        if (!$this->isGranted('VIEW', new JournalContact())) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('ojs_api.contact.handler')->all($limit, $offset);
    }

    /**
     * Get single Contact.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Contact for a given id",
     *   output = "Ojs\ContactBundle\Entity\Contact",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the Contact is not found"
     *   },
     *   views = {"contact"},
     *   section = "contact",
     * )
     *
     * @param int     $id      the Contact id
     *
     * @return array
     *
     * @throws NotFoundHttpException when Contact not exist
     */
    public function getContactAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new Contact.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"contact"},
     *   section = "contact",
     * )
     *
     * @return FormTypeInterface
     */
    public function newContactAction()
    {
        if (!$this->isGranted('CREATE', new JournalContact())) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new ContactType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a Contact from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Contact from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"contact"},
     *   section = "contact",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postContactAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new JournalContact())) {
            throw new AccessDeniedException;
        }
        try {
            $newEntity = $this->container->get('ojs_api.contact.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_contacts', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing Contact from the submitted data or create a new Contact at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the Contact is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"contact"},
     *   section = "contact",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the Contact id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when Contact not exist
     */
    public function putContactAction(Request $request, $id)
    {
        if (!$this->isGranted('CREATE', new JournalContact())) {
            throw new AccessDeniedException;
        }
        try {
            if (!($entity = $this->container->get('ojs_api.contact.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('ojs_api.contact.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('ojs_api.contact.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_contact', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing contact from the submitted data or create a new contact at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"contact"},
     *   section = "contact",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the contact id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when contact not exist
     */
    public function patchContactAction(Request $request, $id)
    {
        try {
            $entity = $this->container->get('ojs_api.contact.handler')->patch(
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
            return $this->routeRedirectView('api_1_get_contact', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete Contact",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "Contact ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"contact"},
     *      section = "contact",
     * )
     *
     */
    public function deleteContactAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException;
        }
        $this->container->get('ojs_api.contact.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a Contact or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return JournalContact
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('ojs_api.contact.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
