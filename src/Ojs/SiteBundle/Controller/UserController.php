<?php
/** 
 * Date: 12.12.14
 * Time: 10:25
 */

namespace Ojs\SiteBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller{
    public function profileAction(Request $request,$slug)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('OjsUserBundle:User')->findOneBy(['username'=>$slug]);
        if(!$user)
            throw new NotFoundHttpException("User not found");
        $data =[];
        $data['user'] = $user;
        $data['me'] = $this->getUser();
        return $this->render('OjsSiteBundle:Site:profile_index.html.twig',$data);
    }
} 