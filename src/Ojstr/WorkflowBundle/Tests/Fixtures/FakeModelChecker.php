<?php

namespace Ojstr\WorkflowBundle\Tests\Fixtures;

class FakeModelChecker {

    public function isClean(FakeModel $model) {
        return ( '' !== $model->getContent() && null !== $model->getContent());
    }

}
