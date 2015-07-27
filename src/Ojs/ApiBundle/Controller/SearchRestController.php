<?php

namespace Ojs\ApiBundle\Controller;

use Elastica\Query;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchRestController extends FOSRestController
{

    /**
     * @Rest\QueryParam(name="q", nullable=false, description="Query text")
     * @Rest\QueryParam(name="page_limit", nullable=true, requirements="\d+", description="Query limit", default="10")
     *
     * @param  ParamFetcher $paramFetcher
     * @param  integer $journalId
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
    public function searchJournalUsersAction(ParamFetcher $paramFetcher, $journalId)
    {
        $em = $this->getDoctrine()->getManager();

        $defaultLimit = 20;
        $limit = ($paramFetcher->get('page_limit') && $defaultLimit >= $paramFetcher->get('page_limit')) ?
            $paramFetcher->get('page_limit') :
            $defaultLimit;

        $journalUsers = $em->getRepository('OjsUserBundle:User')->searchJournalUser(
            $paramFetcher->get('q'),
            $journalId,
            $limit
        );

        return $journalUsers;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="search users in username-email-tags and subjects (accepts regex inputs)",
     *  parameters={
     * {
     *          "name"="q",
     *          "dataType"="string",
     *          "required"="true",
     *          "description"="search term"
     *      }
     *  }
     * )
     * @Rest\Get("/search/user")
     *
     * @param  Request $request
     * @return array
     */
    public function getUsersAction(Request $request)
    {
        $q = $request->get('q');
        $search = $this->container->get('fos_elastica.index.search.user');

        $s1 = new Query\Regexp();
        $s1->setValue('username', $q);

        $s2 = new Query\Regexp();
        $s2->setValue('subjects', $q);

        $s3 = new Query\Regexp();
        $s3->setValue('tags', $q);

        $query = new Query\Bool();
        $query->addShould($s1);
        $query->addShould($s2);
        $query->addShould($s3);

        $results = $search->search($query);
        $data = [];
        foreach ($results as $result) {
            $data[] = array_merge(array('id' => $result->getId()), $result->getData());
        }

        return $data;
    }
}
