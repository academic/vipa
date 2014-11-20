<?php

namespace Ojs\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\UserBundle\Entity\Citation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;

class CitationRestController extends FOSRestController
{

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Parse citations",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     * parameters={
     *      {
     *          "name"="citations",
     *          "dataType"="string",
     *          "required"="true",
     *          "description"="citations separated with newline"
     *      },
     *      {
     *          "name"="apikey",
     *          "dataType"="string",
     *          "required"="true",
     *          "description"="Apikey"
     *      }
     *  }
     * )
     * @Get("/citation/parse")
     */
    public function getCitationParseAction(Request $request)
    {
        $citations = $request->get('citations') ? $request->get('citations') : 12;
        if (empty($citations)) {
            throw new HttpException(400, 'Missing parameter : citations');
        }
        $citationParser = \Okulbilisim\CitationParser\CitationParser();
        $parsedCitations = $citationParser->parse($citations);
        return $parsedCitations;
    }

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
     */
    public function deleteCitationAction($id, Request $request)
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
