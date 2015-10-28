<?php

namespace Ojs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\JournalBundle\Form\Type\SectionType;
use Ojs\JournalBundle\Entity\Section;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;

class JournalSectionRestController extends FOSRestController
{
    /**
     * List all Sections.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing Sections.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many Sections to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getSectionsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('ojs_api.journal_section.handler')->all($limit, $offset);
    }

    /**
     * Get single Section.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Section for a given id",
     *   output = "Ojs\SectionBundle\Entity\Section",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the Section is not found"
     *   }
     * )
     *
     * @param int     $id      the Section id
     *
     * @return array
     *
     * @throws NotFoundHttpException when Section not exist
     */
    public function getSectionAction($id)
    {
        $entity = $this->getOr404($id);
        return $entity;
    }

    /**
     * Presents the form to use to create a new Section.
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
    public function newSectionAction()
    {
        return $this->createForm(new SectionType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a Section from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Section from the submitted data.",
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
    public function postSectionAction(Request $request)
    {
        try {
            $journalService = $this->container->get('ojs.journal_service');
            $newEntity = $this->container->get('ojs_api.journal_section.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_themes', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing Section from the submitted data or create a new Section at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the Section is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the Section id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when Section not exist
     */
    public function putSectionAction(Request $request, $id)
    {
        try {
            $journalService = $this->container->get('ojs.journal_service');
            if (!($entity = $this->container->get('ojs_api.journal_section.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('ojs_api.journal_section.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('ojs_api.journal_section.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_theme', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing journal_section from the submitted data or create a new journal_section at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the journal_section id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when journal_section not exist
     */
    public function patchSectionAction(Request $request, $id)
    {
        try {
            $journalService = $this->container->get('ojs.journal_service');
            $entity = $this->container->get('ojs_api.journal_section.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $entity->getId(),
                'journalId' => $journalService->getSelectedJournal()->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_theme', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete Section",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "Section ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      }
     * )
     *
     */
    public function deleteSectionAction($id)
    {
        $entity = $this->getOr404($id);
        $this->container->get('ojs_api.journal_section.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a Section or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return Section
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('ojs_api.journal_section.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
