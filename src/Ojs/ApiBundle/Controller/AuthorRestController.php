<?php
namespace Ojs\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;

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
        $authors = $this->getDoctrine()->getRepository('OjstrJournalBundle:Author')->findAll();
        if (!is_array($authors) && !count($authors) > 0) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $authors;
    }
}
