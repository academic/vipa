<?php

namespace Ojstr\WorkflowBundle\Validation;

/**
 * Violation of step validations.
 *
 * @author Jeremy Barthe <j.barthe@lexik.fr>
 * @author Gilles Gauthier <g.gauthier@lexik.fr>
 */
class Violation {

    /**
     * @var string
     */
    private $message;

    /**
     * Constructor.
     *
     * @param string $message
     */
    public function __construct($message) {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

}
