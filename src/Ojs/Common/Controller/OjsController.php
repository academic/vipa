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
     */
    public function throw404IfNotFound($entity, $message = 'Not Found')
    {
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans($message));
        }
        return TRUE;
    }

}
