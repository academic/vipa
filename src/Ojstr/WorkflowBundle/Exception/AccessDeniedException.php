<?php

namespace Ojstr\WorkflowBundle\Exception;

class AccessDeniedException extends \Exception {

    /**
     * Construct.
     *
     * @param string $stepName
     */
    public function __construct($stepName) {
        $this->message = sprintf('Access denied. The current user is not allowed to reach the step "%s"', $stepName);
    }

}
