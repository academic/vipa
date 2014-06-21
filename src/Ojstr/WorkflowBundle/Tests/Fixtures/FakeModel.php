<?php

namespace Ojstr\WorkflowBundle\Tests\Fixtures;

use Ojstr\WorkflowBundle\Model\ModelInterface;

class FakeModel implements ModelInterface {

    const STATUS_CREATE = 1;
    const STATUS_VALIDATE = 2;
    const STATUS_REMOVE = 3;

    protected $status;
    protected $content;
    public $data = array();

    public function getWorkflowIdentifier() {
        return 'sample_identifier';
    }

    public function getWorkflowData() {
        return $this->data;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

}
