<?php
/**
 * Created by PhpStorm.
 * User: emreyilmaz
 * Date: 1.06.15
 * Time: 10:22
 */
namespace Ojs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IssueRestController extends FOSRestController
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get issue detail"
     * )
     * @Get("/issue/{id}/detail")
     * @View(serializerGroups={"IssueDetail"})
     */
    public function getIssueDetailAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $issue = $em->find('OjsJournalBundle:Issue', $id);
        if (!$issue) {
            throw new NotFoundHttpException("Issue not exists");
        }

        return $issue;
    }
}
