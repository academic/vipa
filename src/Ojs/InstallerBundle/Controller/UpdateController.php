<?php
/**
 * Date: 14.01.15
 * Time: 13:17
 */

namespace Ojs\InstallerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UpdateController extends Controller
{
    public function indexAction(Request $request)
    {
        $data = [];
        $pwd = $this->get('kernel')->getRootDir();
        $consoleApp = $pwd . '/console';
        $rm = shell_exec("php $consoleApp doctrine:cache:clear-metadata --env=prod");
        $diff = shell_exec("/usr/bin/php $consoleApp doctrine:schema:update --dump-sql --env=prod");
        $data['diff'] = $diff;
        $data['data']['page']='home';
        return $this->render('OjsInstallerBundle:Update:index.html.twig', $data);
    }

    public function updateAction(Request $request)
    {
        $data = [];
        $pwd = $this->get('kernel')->getRootDir();
        $consoleApp = $pwd . '/console';
        $rm = shell_exec("php $consoleApp doctrine:cache:clear-metadata");
        $diff = shell_exec("/usr/bin/php $consoleApp doctrine:schema:update --force --env=prod");
        $data['data']['page']='update';
        $data['diff']=$diff;
        return $this->render('OjsInstallerBundle:Update:update.html.twig', $data);

    }
}