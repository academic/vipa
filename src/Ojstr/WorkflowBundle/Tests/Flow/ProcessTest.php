<?php

namespace Ojstr\WorkflowBundle\Tests\Flow;

use Ojstr\WorkflowBundle\Tests\TestCase;
use Ojstr\WorkflowBundle\DependencyInjection\WorkflowExtension;
use Ojstr\WorkflowBundle\Flow\Process;
use Ojstr\WorkflowBundle\Flow\Step;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProcessTest extends TestCase {

    public function testProcessService() {
        $container = new ContainerBuilder();
        $container->set('next_state_condition', new \stdClass());

        $extension = new WorkflowExtension();
        $extension->load(array($this->getConfig()), $container);

        $process = $container->get('workflow.process.document_proccess');
        $this->assertTrue($process instanceof Process);
        $this->assertTrue($process->getSteps() instanceof ArrayCollection);
        $this->assertEquals(3, $process->getSteps()->count());
        $this->assertTrue($process->getSteps()->get('step_create_doc') instanceof Step);
    }

}
