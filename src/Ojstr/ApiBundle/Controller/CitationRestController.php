<?php

namespace Ojstr\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use Ojstr\UserBundle\Entity\Citation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Ojstr\UserBundle\Form\UserRestType;

class CitationRestController extends FOSRestController {

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Citation Action",
     *  statusCodes={
     *         200="Returned when successful"
     *  }
     * )
     */
    public function getCitationAction($id, Request $request) {
        $citation = $this->getDoctrine()->getRepository('OjstrJournalBundle:Citation')->find($id);
        if (!is_object($citation)) {
            $this->notFound();
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
    public function deleteCitationAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $citation = $this->getDoctrine()->getRepository('OjstrJournalBundle:Citation')->find($id);
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
