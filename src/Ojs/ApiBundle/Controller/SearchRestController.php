<?php

namespace Ojs\ApiBundle\Controller;

use Elastica\Query;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

class SearchRestController extends FOSRestController
{

    /**
     * @Rest\QueryParam(name="q", nullable=false, description="Query text")
     * @Rest\QueryParam(name="page_limit", nullable=true, requirements="\d+", description="Query limit", default="10")
     * @Rest\View(serializerGroups={"search"})
     *
     * @param  ParamFetcher $paramFetcher
     * @return Response
     *
     * @Rest\Get("/search/journal/{journalId}/users")
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Search Journal's Users",
     *   output = "Ojs\UserBundle\Entity\User[]",
     *   statusCodes = {
     *     "200" = "Users listed successfully",
     *     "403" = "Access Denied"
     *   }
     * )
     */
    public function searchJournalUsersAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();

        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$journal) {
            return $this->createNotFoundException();
        }

        $defaultLimit = 20;
        $limit = ($paramFetcher->get('page_limit') && $defaultLimit >= $paramFetcher->get('page_limit')) ?
            $paramFetcher->get('page_limit') :
            $defaultLimit;

        $journalUsers = $em->getRepository('OjsUserBundle:User')->searchJournalUser(
            $paramFetcher->get('q'),
            $journal,
            $limit
        );

        return $journalUsers;
    }

}
