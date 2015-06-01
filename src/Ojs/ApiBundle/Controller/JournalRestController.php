<?php

namespace Ojs\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;

class JournalRestController extends FOSRestController
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Journal detail"
     * )
     * @Get("/journal/{id}/detail")
     * @View(serializerGroups={"JournalDetail"})
     */
    public function getJournalDetailAction($id)
    {
        $journal = $this->getDoctrine()->getRepository('OjsJournalBundle:Journal')->find($id);
        if (!is_object($journal)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $journal;
    }
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Journal Issues"
     * )
     * @Get("/journal/{id}/issues")
     *
     */
    public function getJournalIssuesAction($id)
    {
        $journal = $this->getDoctrine()->getRepository('OjsJournalBundle:Journal')->find($id);
        if (!is_object($journal)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $journal->getIssues();
    }



    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Specific Journal Of Users Action",
     *  parameters={
     *      {
     *          "name"="page",
     *          "dataType"="integer",
     *          "required"="true",
     *          "description"="offset page"
     *      },
     *      {
     *          "name"="limit",
     *          "dataType"="integer",
     *          "required"="true",
     *          "description"="limit"
     *      }
     *  }
     * )
     * @Get("/journal/{id}/users")
     *
     * @param  Request $request
     * @param $id
     * @return mixed
     */
    public function getJournalUsersAction(Request $request, $id)
    {
        $limit = $request->get('limit');
        $page = (int) $request->get('page'); // page is not a mandotary parameter
        if (empty($limit)) {
            throw new HttpException(400, 'Missing parameter : limit');
        }

        return $this->get('ojs.journal_service')->getUsers($id, $page, $limit);
    }
}
