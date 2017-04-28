<?php

namespace Vipa\InstallerBundle\Controller;

use Vipa\CoreBundle\Controller\VipaController as Controller;

class CheckController extends Controller
{

    public function checkAction()
    {
        $data['page'] = 'check';
        require_once $this->get('kernel')->getRootDir().DIRECTORY_SEPARATOR.'SymfonyRequirements.php';

        $symfonyRequirements = new \SymfonyRequirements();

        $iniPath = $symfonyRequirements->getPhpIniConfigPath();
        $data['result'] = '
        <div class="alert alert-warning"><ul>
        '.($iniPath ? sprintf(
                "<li>Configuration file used by PHP: %s</li>",
                $iniPath
            ) : "<li>WARNING: No configuration file (php.ini) used by PHP!</li>").
            '<li>The PHP CLI can use a different php.ini file</li>
        <li>than the one used with your web server.</li>';
        if ('\\' == DIRECTORY_SEPARATOR) {
            $data['result'] .= '<li>(especially on the Windows platform)</li>';
        }
        $data['result'] .= '<li>To be on the safe side, please also launch the requirements check</li>
        <li>from your web server using the web/config.php script.</li>
        </ul></div>';

        $data['result'] .= '<div class="table-responsive"><table id="checkTable" class="table table-striped">';

        foreach ($symfonyRequirements->getRequirements() as $req) {
            /** @var $req \Requirement */
            $data['result'] .= $this->echoRequirement($req);
        }

        foreach ($symfonyRequirements->getRecommendations() as $req) {
            $data['result'] .= $this->echoRequirement($req);
        }

        $data['result'] .= '</table></div>';

        return $this->render('VipaInstallerBundle:Default:check.html.twig', array('data' => $data));
    }

    /**
     * Prints a Requirement instance
     */
    private function echoRequirement(\Requirement $requirement)
    {
        $result = $requirement->isFulfilled() ? 'OK' : ($requirement->isOptional() ? 'WARNING' : 'ERROR');
        $data = '';
        switch (rtrim(ltrim(str_pad($result, 9)))) {
            case 'OK':
                $data .= '<tr class="success">';
                break;
            case 'WARNING':
                $data .= '<tr class="warning">';
                break;
            case 'ERROR':
                $data .= '<tr class="danger">';
                break;
            default:

                $data .= '<tr>';
                break;
        }
        $data .= '<td>'.str_pad($result, 9).'</td>';
        $data .= '<td>'.$requirement->getTestMessage()."</td>";

        if (!$requirement->isFulfilled()) {
            $data .= '<td>'.$requirement->getHelpText().'</td>';
        }
        $data .= '</tr>';

        return $data;
    }
}
