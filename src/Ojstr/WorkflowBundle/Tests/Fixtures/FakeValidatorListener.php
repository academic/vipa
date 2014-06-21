<?php

namespace Ojstr\WorkflowBundle\Tests\Fixtures;

use Ojstr\WorkflowBundle\Event\ValidateStepEvent;

class FakeValidatorListener {

    public function valid(ValidateStepEvent $event) {
        
    }

    public function invalid(ValidateStepEvent $event) {
        $event->addViolation('Validation error!');
    }

}
