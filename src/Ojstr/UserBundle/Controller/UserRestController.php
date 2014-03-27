<?php
namespace Ojstr\UserBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Ojstr\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserRestController extends Controller
{
    public function getUserAction($username)
    {
        $user = $this->getDoctrine()->getRepository('OjstrUserBundle:User')->findOneByUsername($username);
        if (!is_object($user)) {
            throw $this->createNotFoundException();
        }
        return $user;
    }

    public function deleteUserAction($user_id, $soft_delete = TRUE)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var User $user
         */
        $user = $this->getDoctrine()->getRepository('OjstrUserBundle:User')->findOneById($user_id);
        if (!is_object($user)) {
            throw $this->createNotFoundException();
        }
        if ($soft_delete) {
            $user->setStatus(-1);
        } else {
            $em->remove($user);
            $em->flush();
        }
        return array("user_id" => $user_id);
    }
}