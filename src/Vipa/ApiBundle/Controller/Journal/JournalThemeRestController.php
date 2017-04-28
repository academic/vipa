<?php

namespace Vipa\ApiBundle\Controller\Journal;

use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Vipa\JournalBundle\Form\Type\JournalThemeType;
use Vipa\JournalBundle\Entity\JournalTheme;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;
use Vipa\ApiBundle\Controller\ApiController;

class JournalThemeRestController extends ApiController
{
    /**
     * List all JournalThemes.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"journaltheme"},
     *   section = "journaltheme",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing JournalThemes.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many JournalThemes to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getThemesAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'theme')) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('vipa_api.journal_theme.handler')->all($limit, $offset);
    }

    /**
     * Get single JournalTheme.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a JournalTheme for a given id",
     *   output = "Vipa\JournalThemeBundle\Entity\JournalTheme",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the JournalTheme is not found"
     *   },
     *   views = {"journaltheme"},
     *   section = "journaltheme",
     * )
     *
     * @param int     $id      the JournalTheme id
     *
     * @return array
     *
     * @throws NotFoundHttpException when JournalTheme not exist
     */
    public function getThemeAction($id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'theme')) {
            throw new AccessDeniedException;
        }
        $entity = $this->getOr404($id);
        return $entity;
    }

    /**
     * Presents the form to use to create a new JournalTheme.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"journaltheme"},
     *   section = "journaltheme",
     * )
     *
     * @return FormTypeInterface
     */
    public function newThemeAction()
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'theme')) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new JournalThemeType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a JournalTheme from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new JournalTheme from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"journaltheme"},
     *   section = "journaltheme",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postThemeAction(Request $request)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'theme')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('vipa.journal_service');
            $newEntity = $this->container->get('vipa_api.journal_theme.handler')->post(
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
     * Update existing JournalTheme from the submitted data or create a new JournalTheme at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the JournalTheme is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"journaltheme"},
     *   section = "journaltheme",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the JournalTheme id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when JournalTheme not exist
     */
    public function putThemeAction(Request $request, $id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'theme')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('vipa.journal_service');
            if (!($entity = $this->container->get('vipa_api.journal_theme.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('vipa_api.journal_theme.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('vipa_api.journal_theme.handler')->put(
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
     * Update existing journal_theme from the submitted data or create a new journal_theme at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"journaltheme"},
     *   section = "journaltheme",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the journal_theme id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when journal_theme not exist
     */
    public function patchThemeAction(Request $request, $id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'theme')) {
            throw new AccessDeniedException;
        }
        try {
            $journalService = $this->container->get('vipa.journal_service');
            $entity = $this->container->get('vipa_api.journal_theme.handler')->patch(
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
     *      description = "Delete JournalTheme",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "JournalTheme ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"journaltheme"},
     *      section = "journaltheme",
     * )
     *
     */
    public function deleteThemeAction($id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('DELETE', $journal, 'theme')) {
            throw new AccessDeniedException;
        }
        $entity = $this->getOr404($id);
        $this->container->get('vipa_api.journal_theme.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a JournalTheme or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return JournalTheme
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('vipa_api.journal_theme.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
