<?php

namespace Vipa\InstallerBundle\Controller;

use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\InstallerBundle\Entity\Config;
use Vipa\InstallerBundle\Form\Type\ConfigType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

class ConfigController extends Controller
{

    public function configureAction()
    {
        $data = [];
        $data['data']['page'] = 'config';
        $parametersFile = $this->get('kernel')->getRootDir().DIRECTORY_SEPARATOR.'config.'.DIRECTORY_SEPARATOR.'parameters.yml';
        $parametersFileDist = $parametersFile.'.dist';
        $formData = new Config();
        $parser = new Parser();
        if (file_exists($parametersFile)) {
            $formDataFile = $parser->parse(file_get_contents($parametersFile));
        } else {
            $formDataFile = $parser->parse(file_get_contents($parametersFileDist));
        }
        foreach ($formDataFile['parameters'] as $key => $value) {
            $setter = 'set'.implode(
                    '',
                    array_map(
                        function ($s) {
                            return ucfirst($s);
                        },
                        explode('_', $key)
                    )
                );
            if (method_exists($formData, $setter)) {
                $formData->{$setter}($value);
            }
        }
        $form = $this->createForm(
            new ConfigType(),
            $formData,
            [
                'method' => 'post',
                'action' => $this->get('router')->generate('vipa_installer_save_configure'),
            ]
        );
        $data['form'] = $form->createView();

        return $this->render("VipaInstallerBundle:Default:configure.html.twig", $data);
    }

    public function saveAction(Request $request)
    {
        $entity = new Config();
        $form = $this->createForm(
            new ConfigType(),
            $entity,
            [
                'method' => 'post',
                'action' => $this->get('router')->generate('vipa_installer_save_configure'),
            ]
        );
        $form->handleRequest($request);
        if ($form->isValid()) {
            $dumper = new Dumper();
            $yaml = $dumper->dump($entity->toArray(), 2, 0);
            $parametersFile = $parametersFile = $this->get('kernel')->getRootDir().DIRECTORY_SEPARATOR.'config.'.DIRECTORY_SEPARATOR.'parameters.yml';
            $fs = new Filesystem();
            $fs->dumpFile($parametersFile, $yaml);

            return new RedirectResponse('/install/setup');
        }

        return new Response();
    }
}
