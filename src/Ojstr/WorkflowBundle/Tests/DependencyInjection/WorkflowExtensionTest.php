<?php

namespace Ojstr\WorkflowBundle\Tests\DependencyInjection;

use Ojstr\WorkflowBundle\Tests\TestCase;
use Ojstr\WorkflowBundle\DependencyInjection\WorkflowExtension;
use Ojstr\WorkflowBundle\Flow\Process;
use Ojstr\WorkflowBundle\Handler\ProcessAggregator;
use Ojstr\WorkflowBundle\Handler\ProcessHandler;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class WorkflowExtensionTest extends TestCase {

    public function testLoad() {
        $container = new ContainerBuilder();

        // fake entity manager and security context services
        $container->set('doctrine.orm.entity_manager', $this->getMockSqliteEntityManager());
        $container->set('security.context', $this->getMockSecurityContext());
        $container->set('event_dispatcher', new EventDispatcher());
        $container->set('next_state_condition', new \stdClass());

        // simple config
        $extension = new WorkflowExtension();
        $extension->load(array($this->getSimpleConfig()), $container);

        $this->assertTrue($container->getDefinition('workflow.process.document_proccess') instanceof Definition);

        // config with a process
        $extension = new WorkflowExtension();
        $extension->load(array($this->getConfig()), $container);

        $this->assertTrue($container->getDefinition('workflow.process.document_proccess') instanceof Definition);
        $this->assertTrue($container->getDefinition('workflow.process.document_proccess.step.step_create_doc') instanceof Definition);
        $this->assertTrue($container->getDefinition('workflow.process.document_proccess.step.step_validate_doc') instanceof Definition);
        $this->assertTrue($container->getDefinition('workflow.process.document_proccess.step.step_remove_doc') instanceof Definition);
        $this->assertTrue($container->getDefinition('workflow.handler.document_proccess') instanceof Definition);

        $processHandlerFactory = $container->get('workflow.process_aggregator');
        $this->assertTrue($processHandlerFactory instanceof ProcessAggregator);
        $this->assertTrue($processHandlerFactory->getProcess('document_proccess') instanceof Process);

        $processHandler = $container->get('workflow.handler.document_proccess');
        $this->assertTrue($processHandler instanceof ProcessHandler);
    }

}
