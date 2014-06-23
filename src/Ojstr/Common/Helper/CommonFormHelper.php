<?php

namespace Ojstr\Common\Helper;

class CommonFormHelper {

    function createDeleteForm($app, $id, $path_name = NULL) {
        return $app->createFormBuilder()
                        ->setAction($app->generateUrl($path_name, array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array(
                            'label' => $app->get('translator')->trans('Delete'),
                            'attr' => array('class' => 'btn btn-danger', 'onclick' => 'return confirm("' .
                                $app->get('translator')->trans('Are you sure?') . '"); ')
                        ))
                        ->getForm();
    }

}
