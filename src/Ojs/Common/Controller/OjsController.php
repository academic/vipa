<?php

namespace Ojs\Common\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Ojs Base Controller controller.
 *
 */
class OjsController extends Controller
{

    /**
     *
     * @param  mixed   $entity
     * @param string $message custom not found message
     * @return boolean 
     * @throws NoResultException
     */
    public function throw404IfNotFound($entity, $message = 'Not Found')
    {
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans($message));
        }
        return TRUE;
    }

    /**
     * @param $text
     * @return bool
     */
    public function successFlashBag($text)
    {
        $session = $this->get('session');
        $flashBag = $session->getFlashBag();
        $translator = $this->get('translator');
        $flashBag->add('success',$translator->trans($text));
        return true;
    }

    /**
     * @param $text
     * @return bool
     */
    public function errorFlashBag($text)
    {
        $session = $this->get('session');
        $flashBag = $session->getFlashBag();
        $translator = $this->get('translator');
        $flashBag->add('error',$translator->trans($text));
        return true;
    }
}
