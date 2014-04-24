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

class JournalRestController extends FOSRestController {

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Journal Action"
     * )
     */
    public function getJournalAction($id) {
        $journal = $this->getDoctrine()->getRepository('OjstrJournalBundle:Journal')->find($id);
        if (!is_object($journal)) {
            $this->notFound();
        }
        return $journal;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Journal Users Action",
     *  parameters={
     *      {
     *          "name"="page",
     *          "dataType"="integer",
     *          "required"="true",
     *          "description"="offset page"
     *      },
     *      {
     *          "name"="limit",
     *          "dataType"="integer",
     *          "required"="true",
     *          "description"="limit"
     *      }
     *  }
     * )
     */
    public function getJournalUsersAction($id) {
        $limit = $request->get('limit');
        $page = $request->get('page');
        if (empty($limit)) {
            throw new HttpException(400, 'Missing parameter : limit');
        }
        if (empty($page)) {
            throw new HttpException(400, 'Missing parameter : page');
        }
        $user = $this->getDoctrine()->
                getRepository('OjstrUserBundle:UserJournalRole')
                ->createQueryBuilder()
                ->where('journal_id > :id')
                ->setParameter('id', $id)
                //->orderBy('id', 'ASC')
                ->setMaxResults($limit)
                ->setFirstResult($page)
                ->getQuery()
                ->getResults();
        return $users;
    }

}
