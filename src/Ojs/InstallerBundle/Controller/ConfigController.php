<?php

namespace Ojs\InstallerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Unifik\DatabaseConfigBundle\Entity\Parameter;
use Doctrine\Common\Persistence\ObjectManager;

class ConfigController extends Controller {

    public function configureAction() {
        $data['page'] = 'config';
        

//        $parameter = new Parameter();
//        $parameter->setName('database_driver');
//        $parameter->setValue('pdo_mysql');
//        // ObjectManager $manager
//        $manager->persist($parameter);
//        $manager->flush();


        return $this->render("OjsInstallerBundle:Default:configure.html.twig", array('data' => $data));
    }

}
