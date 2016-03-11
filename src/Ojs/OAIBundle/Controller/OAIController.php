<?php

namespace Ojs\OAIBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OAIController
 * @package Ojs\OAIBundle\Controller
 */
abstract class OAIController extends Controller
{
    /**
     * Returns a XML response
     * @param string $template
     * @param array $data
     * @return Response
     */
    protected function response($template, $data = [])
    {
        $response = new Response();
        $response->headers->set('content-type', 'text/xml');
        return $this->render($template, $data, $response);
    }

    /**
     * Index action
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $verb = $request->get('verb');

        switch ($verb) {
            case 'Identify':
                return $this->identifyAction($request);
            case 'GetRecord':
                return $this->getRecordAction($request);
            case 'ListRecords':
                return $this->recordsAction($request);
            case 'ListSets':
                return $this->listSetsAction($request);
            case 'ListIdentifiers':
                return $this->listIdentifierAction($request);
            case 'ListMetadataFormats':
                return $this->listMetadataFormatsAction();
            default:
                return $this->response('OjsOAIBundle:Default:index.xml.twig');
        }
    }

    /**
     * Action for the list metadata formats verb
     * @return Response
     */
    public function listMetadataFormatsAction()
    {
        return $this->response('OjsOAIBundle:Default:metadata_formats.xml.twig');
    }

    /**
     * Action for the identify verb
     * @param Request $request
     * @return Response
     */
    public abstract function identifyAction(Request $request);

    /**
     * Action for the records verb
     * @param Request $request
     * @return Response
     */
    public abstract function recordsAction(Request $request);

    /**
     * Action for the list sets verb
     * @param Request $request
     * @return Response
     */
    public abstract function listSetsAction(Request $request);

    /**
     * Action for the list identifier verb
     * @param Request $request
     * @return Response
     */
    public abstract function listIdentifierAction(Request $request);

    /**
     * Action for the get record verb
     * @param Request $request
     * @return Response
     */
    public abstract function getRecordAction(Request $request);
}
