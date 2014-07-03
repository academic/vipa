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


class ContactRestController extends FOSRestController { 

 /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Journal Issues"
     * )
     * @Get("/contacts")
     */
    public function getContactsAction() {
        $contacts = $this->getDoctrine()->getRepository('OjstrJournalBundle:Contact')->findAll();
        
        if (!is_array($contacts) && !count($contacts) > 0) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }
        return $contacts;
    }
}