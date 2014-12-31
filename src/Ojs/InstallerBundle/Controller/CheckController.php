<?php

namespace Ojs\InstallerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\DistributionBundle\SensioDistributionBundle;

class CheckController extends Controller {

    public function checkAction() {
        $data['page']='check';
        require_once __DIR__ . '/../../../../app/SymfonyRequirements.php';

        $symfonyRequirements = new \SymfonyRequirements();

        $iniPath = $symfonyRequirements->getPhpIniConfigPath();
        $data['result'] = '
        <div class="alert alert-warning"><ul>
        ' . ($iniPath ? sprintf("<li>Configuration file used by PHP: %s</li>", $iniPath) : "<li>WARNING: No configuration file (php.ini) used by PHP!</li>") .
                '<li>The PHP CLI can use a different php.ini file</li>
        <li>than the one used with your web server.</li>';
        if ('\\' == DIRECTORY_SEPARATOR) {
            $data['result'].='<li>(especially on the Windows platform)</li>';
        }
        $data['result'].= '<li>To be on the safe side, please also launch the requirements check</li>
        <li>from your web server using the web/config.php script.</li>
        </ul></div>';



        $data['result'].= '<table class="table table-striped">';

        $checkPassed = true;
        foreach ($symfonyRequirements->getRequirements() as $req) {
            /** @var $req Requirement */
            $data['result'].= $this->echo_requirement($req);
            if (!$req->isFulfilled()) {
                $checkPassed = false;
            }
        }


        foreach ($symfonyRequirements->getRecommendations() as $req) {
            $data['result'].= $this->echo_requirement($req);
        }

        $data['result'].= '</table>';
        return $this->render('OjsInstallerBundle:Default:check.html.twig', array('data' => $data));
    }

    /**
     * Prints a Requirement instance
     */
    public function echo_requirement(\Requirement $requirement) {
        $result = $requirement->isFulfilled() ? 'OK' : ($requirement->isOptional() ? 'WARNING' : 'ERROR');
$data= '';
        switch (rtrim(ltrim(str_pad($result, 9)))) {
            case 'OK':
                $data.= '<tr class="success">';
                break;
            case 'WARNING':
                $data.= '<tr class="warning">';
                break;
            case 'ERROR':
                $data.= '<tr class="danger">';
                break;
            default:

                $data.= '<tr>';
                break;
        }
        $data.= '<td>' . str_pad($result, 9) . '</td>';
        $data.= '<td>' . $requirement->getTestMessage() . "</td>";

        if (!$requirement->isFulfilled()) {
            $data.= '<td>' . $requirement->getHelpText() . '</td>';
        }
        $data.= '</tr>';
        return $data;
    }

}
