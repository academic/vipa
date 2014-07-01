<?php

namespace Ojstr\Common\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Ojs Base Controller controller.
 *
 */
class OjsController extends Controller {

    /**
     * 
     * @param mixed $entity
     * @return boolean
     */
    public function throw404IfNotFound($entity) {
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        return TRUE;
    }

}
