<?php

namespace Ojstr\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\FOSRestController;

class TestRestController extends FOSRestController
{
    public function getTestAction()
    {
        $res = array("status" => "ok");

        return $res;
    }

}
