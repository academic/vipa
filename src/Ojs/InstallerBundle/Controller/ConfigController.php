<?php

namespace Ojs\InstallerBundle\Controller;

use Ojs\InstallerBundle\Entity\Config;
use Ojs\InstallerBundle\Form\ConfigType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Dumper\YamlDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Parser;
use Unifik\DatabaseConfigBundle\Entity\Parameter;
use Doctrine\Common\Persistence\ObjectManager;

class ConfigController extends Controller {

    public function configureAction(Request $request) {
        $data=[];
        $data['data']['page'] = 'config';
        $parametersFile = __DIR__.'/../../../../app/config/parameters.yml';
        $parametersFileDist = $parametersFile.'.dist';
        $formData = new Config();
        $parser = new Parser();
        if(file_exists($parametersFile)){
            $formDataFile = $parser->parse(file_get_contents($parametersFile));
        }else{
            $formDataFile = $parser->parse(file_get_contents($parametersFileDist));

        }
        foreach($formDataFile['parameters'] as $key=>$value){
            $setter = 'set'.join('',array_map(function($s){return ucfirst($s);},explode('_',$key)));
            $formData->{$setter}($value);
        }
        $form = $this->createForm(new ConfigType(),$formData,[]);
        $data['form'] = $form->createView();
        return $this->render("OjsInstallerBundle:Default:configure.html.twig", $data);
    }

}
