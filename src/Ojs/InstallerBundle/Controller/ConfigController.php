<?php

namespace Ojs\InstallerBundle\Controller;

use Ojs\InstallerBundle\Entity\Config;
use Ojs\InstallerBundle\Form\ConfigType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

class ConfigController extends Controller
{

    public function configureAction(Request $request)
    {
        $data = [];
        $data['data']['page'] = 'config';
        $parametersFile = __DIR__ . '/../../../../app/config/parameters.yml';
        $parametersFileDist = $parametersFile . '.dist';
        $formData = new Config();
        $parser = new Parser();
        if (file_exists($parametersFile)) {
            $formDataFile = $parser->parse(file_get_contents($parametersFile));
        } else {
            $formDataFile = $parser->parse(file_get_contents($parametersFileDist));

        }
        foreach ($formDataFile['parameters'] as $key => $value) {
            $setter = 'set' . join('', array_map(function ($s) {
                    return ucfirst($s);
                }, explode('_', $key)));
            $formData->{$setter}($value);
        }
        $form = $this->createForm(new ConfigType(), $formData, [
            'method' => 'post',
            'action' => $this->get('router')->generate('ojs_installer_save_configure')
        ]);
        $data['form'] = $form->createView();
        return $this->render("OjsInstallerBundle:Default:configure.html.twig", $data);
    }

    public function saveAction(Request $request)
    {
        $entity = new Config();
        $form = $this->createForm(new ConfigType(), $entity, [
            'method' => 'post',
            'action' => $this->get('router')->generate('ojs_installer_save_configure')
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {

            $dumper = new Dumper();
            $yaml = $dumper->dump($entity->toArray(), 2, 0);
            $parametersFile = __DIR__ . '/../../../../app/config/parameters.yml';
            $fs = new Filesystem();
            $fs->dumpFile($parametersFile, $yaml);
            return new RedirectResponse('/install/setup');
        }

    }
}
