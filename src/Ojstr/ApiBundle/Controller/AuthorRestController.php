<?php 
namespace Ojstr\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use Ojstr\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Ojstr\UserBundle\Form\UserRestType;
use FOS\RestBundle\Controller\Annotations\Get;


class AuthorRestController extends FOSRestController { 

 /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Journal Issues"
     * )
     * @Get("/authors")
     */
    public function getAuthorsAction() {
        $journal = $this->getDoctrine()->getRepository('OjstrJournalBundle:Author')->findAll();
        if (!is_object($journal)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }
        return $journal;
    }
}