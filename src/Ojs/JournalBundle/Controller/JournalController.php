<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Journal controller.
 */
class JournalController extends Controller
{
    /**
     * @return Response
     */
    public function applyAction()
    {
        return $this->render('OjsJournalBundle:Journal:apply.html.twig', array());
    }
}
