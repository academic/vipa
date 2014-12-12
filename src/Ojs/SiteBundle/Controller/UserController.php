<?php
/** 
 * Date: 12.12.14
 * Time: 10:25
 */

namespace Ojs\SiteBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller{
    public function profileAction(Request $request,$slug)
    {
        return new Response($slug);
    }
} 