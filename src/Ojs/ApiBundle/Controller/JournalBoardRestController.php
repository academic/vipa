<?php

namespace Ojs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\JournalBundle\Form\Type\BoardType;
use Ojs\JournalBundle\Entity\Board;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;

class JournalBoardRestController extends FOSRestController
{
    /**
     * List all Boards.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing Boards.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many Boards to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getBoardsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('ojs_api.journal_board.handler')->all($limit, $offset);
    }

    /**
     * Get single Board.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Board for a given id",
     *   output = "Ojs\BoardBundle\Entity\Board",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the Board is not found"
     *   }
     * )
     *
     * @param int     $id      the Board id
     *
     * @return array
     *
     * @throws NotFoundHttpException when Board not exist
     */
    public function getBoardAction($id)
    {
        $entity = $this->getOr404($id);
        return $entity;
    }

    /**
     * Presents the form to use to create a new Board.
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
    public function newBoardAction()
    {
        return $this->createForm(new BoardType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a Board from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Board from the submitted data.",
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
    public function postBoardAction(Request $request)
    {
        try {
            $journalService = $this->container->get('ojs.journal_service');
            $newEntity = $this->container->get('ojs_api.journal_board.handler')->post(
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
     * Update existing Board from the submitted data or create a new Board at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the Board is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the Board id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when Board not exist
     */
    public function putBoardAction(Request $request, $id)
    {
        try {
            $journalService = $this->container->get('ojs.journal_service');
            if (!($entity = $this->container->get('ojs_api.journal_board.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('ojs_api.journal_board.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('ojs_api.journal_board.handler')->put(
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
     * Update existing journal_board from the submitted data or create a new journal_board at a specific location.
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
     * @param int     $id      the journal_board id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when journal_board not exist
     */
    public function patchBoardAction(Request $request, $id)
    {
        try {
            $journalService = $this->container->get('ojs.journal_service');
            $entity = $this->container->get('ojs_api.journal_board.handler')->patch(
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
     *      description = "Delete Board",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "Board ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      }
     * )
     *
     */
    public function deleteBoardAction($id)
    {
        $entity = $this->getOr404($id);
        $this->container->get('ojs_api.journal_board.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a Board or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return Board
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('ojs_api.journal_board.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
