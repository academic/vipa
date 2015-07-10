<?php

namespace Ojs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CitationRestController extends FOSRestController
{

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Citation Action",
     *  statusCodes={
     *         200="Returned when successful"
     *  }
     * )
     * @Get("/journal/{id}/citations")
     *
     * @param $id
     * @return object
     */
    public function getCitationAction($id)
    {
        $citation = $this->getDoctrine()->getRepository('OjsJournalBundle:Citation')->find($id);
        if (!is_object($citation)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $citation;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Delete a citation with it's settings" ,
     *  method="DELETE",
     *  statusCodes={
     *         204="Returned when successful"
     *  }
     * )
     *
     * @param $id
     */
    public function deleteCitationAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $citation = $this->getDoctrine()->getRepository('OjsJournalBundle:Citation')->find($id);
        $citation_settings = $citation->getSettings();
        $articles = $citation->getArticles();
        // remove settings
        foreach ($citation_settings as $setting) {
            $em->remove($setting);
        }
        // remove relation
        foreach ($articles as $article) {
            $em->remove($article);
        }
        $em->remove($citation);
        $em->flush();
    }
}
