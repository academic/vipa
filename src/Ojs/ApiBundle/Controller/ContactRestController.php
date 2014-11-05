<?php
namespace Ojs\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;

class ContactRestController extends FOSRestController
{
 /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Contacts"
     * )
     * @Get("/contacts")
     */
    public function getContactsAction()
    {
        $contacts = $this->getDoctrine()->getRepository('OjstrJournalBundle:Contact')->findAll();

        if (!is_array($contacts) && !count($contacts) > 0) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $contacts;
    }
}
