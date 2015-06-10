<?php
namespace Ojs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthorRestController extends FOSRestController
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Authors"
     * )
     * @Get("/authors")
     */
    public function getAuthorsAction()
    {
        $authors = $this->getDoctrine()->getRepository('OjsJournalBundle:Author')->findAll();

        if (!is_array($authors) && !count($authors) > 0) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $authors;
    }
}
