<?php

namespace Ojs\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException; 
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;  
use FOS\RestBundle\Controller\Annotations\View as RestView;

class InstitutionRestController extends FOSRestController {

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Institutions"
     * )
     * @Get("/institution/bulk/{page}/{limit}")
     */
    public function getInstitutionsAction($page = 0, $limit = 10)
    {
        $institutions = $this->getDoctrine()->getManager()
                ->createQuery('SELECT i FROM OjsJournalBundle:Institution i')
                ->setFirstResult($page)
                ->setMaxResults($limit)
                ->getResult();
        if (!is_array($institutions)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $institutions;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Specific Institution"
     * )
     * @Get("/institution/{id}")
     */
    public function getInstitutionAction($id)
    {
        $institution = $this->getDoctrine()->getRepository('OjsJournalBundle:Institution')->find($id);
        if (!is_object($institution)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }
        return $institution;
    }

}
