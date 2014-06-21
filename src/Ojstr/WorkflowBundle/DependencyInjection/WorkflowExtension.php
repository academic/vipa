<?php

namespace Ojstr\WorkflowBundle\DependencyInjection;

use Ojstr\WorkflowBundle\Flow\NextStateInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Yaml\Parser;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class WorkflowExtension extends Extension {

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('workflow.process_handler.class', $config['classes']['process_handler']);

        // build process definitions
        $processReferences = $this->buildProcesses($config['processes'], $container, $config['classes']['process'], $config['classes']['step']);
        $this->buildProcessHandlers($processReferences, $container, $config['classes']['process_handler']);

        // inject processes into ProcessAggregator (not possible from a CompilerPass because definitions are loaded from Extension class)
        if ($container->hasDefinition('workflow.process_aggregator')) {
            $container->findDefinition('workflow.process_aggregator')->replaceArgument(0, $processReferences);
        }
    }

    /**
     * Build process handler (factories) definitions from configuration.
     *
     * @param array            $processReferences
     * @param ContainerBuilder $container
     * @param string           $processHandlerClass
     */
    protected function buildProcessHandlers($processReferences, $container, $processHandlerClass) {
        foreach ($processReferences as $processName => $processReference) {
            $definition = new Definition($processHandlerClass, array(
                new Reference(sprintf('workflow.process.%s', $processName)),
                new Reference('workflow.model_storage'),
                new Reference('event_dispatcher'),
            ));

            $definition->addMethodCall('setSecurityContext', array(new Reference('security.context')));

            $container->setDefinition(sprintf('workflow.handler.%s', $processName), $definition);
        }
    }

    /**
     * Build process definitions from configuration.
     *
     * @param array            $processes
     * @param ContainerBuilder $container
     * @param string           $processClass
     * @param string           $stepClass
     *
     * @return array
     */
    protected function buildProcesses($processes, $container, $processClass, $stepClass) {
        $processReferences = array();

        foreach ($processes as $processName => $processConfig) {
            if (!empty($processConfig['import'])) {
                if (is_file($processConfig['import'])) {
                    $yaml = new Parser();
                    $config = $yaml->parse(file_get_contents($processConfig['import']));

                    $processConfig = array_merge($processConfig, $config[$processName]);
                } else {
                    throw new \InvalidArgumentException(sprintf('Can\'t load process from file "%s"', $processConfig['import']));
                }
            }

            $stepReferences = $this->buildSteps($processName, $processConfig['steps'], $container, $stepClass);

            $definition = new Definition($processClass, array(
                $processName,
                $stepReferences,
                $processConfig['start'],
                $processConfig['end'],
            ));

            $definition->setPublic(false)
                    ->addTag('workflow.process', array('alias' => $processName));

            $processReference = sprintf('workflow.process.%s', $processName);
            $container->setDefinition($processReference, $definition);

            $processReferences[$processName] = new Reference($processReference);
        }

        return $processReferences;
    }

    /**
     * Build steps definitions from configuration.
     *
     * @param string           $processName
     * @param array            $steps
     * @param ContainerBuilder $container
     * @param string           $stepClass
     *
     * @return array
     */
    protected function buildSteps($processName, $steps, $container, $stepClass) {
        $stepReferences = array();

        foreach ($steps as $stepName => $stepConfig) {
            $definition = new Definition($stepClass, array(
                $stepName,
                $stepConfig['label'],
                array(),
                $stepConfig['model_status'],
                $stepConfig['roles'],
                $stepConfig['on_invalid'],
            ));

            $this->addStepNextStates($definition, $stepConfig['next_states'], $processName);

            $definition->setPublic(false)
                    ->addTag(sprintf('workflow.process.%s.step', $processName), array('alias' => $stepName));

            $stepReference = sprintf('workflow.process.%s.step.%s', $processName, $stepName);
            $container->setDefinition($stepReference, $definition);

            $stepReferences[$stepName] = new Reference($stepReference);
        }

        return $stepReferences;
    }

    /**
     * Add all next states to the step definition.
     *
     * @param  Definition                $step
     * @param  array                     $stepsNextStates
     * @param  string                    $processName
     * @throws \InvalidArgumentException
     */
    protected function addStepNextStates(Definition $step, $stepsNextStates, $processName) {
        foreach ($stepsNextStates as $stateName => $data) {
            if (NextStateInterface::TYPE_STEP === $data['type']) {
                $step->addMethodCall('addNextState', array(
                    $stateName,
                    $data['type'],
                    new Reference(sprintf('workflow.process.%s.step.%s', $processName, $data['target']))
                ));
            } elseif (NextStateInterface::TYPE_STEP_OR === $data['type']) {
                $targets = array();

                foreach ($data['target'] as $stepName => $condition) {
                    $serviceId = null;
                    $method = null;

                    if (!empty($condition)) {
                        list($serviceId, $method) = explode(':', $condition);
                    }

                    $targets[] = array(
                        'target' => new Reference(sprintf('workflow.process.%s.step.%s', $processName, $stepName)),
                        'condition_object' => null !== $serviceId ? new Reference($serviceId) : null,
                        'condition_method' => $method,
                    );
                }

                $step->addMethodCall('addNextStateOr', array($stateName, $data['type'], $targets));
            } elseif (NextStateInterface::TYPE_PROCESS === $data['type']) {
                $step->addMethodCall('addNextState', array(
                    $stateName,
                    $data['type'],
                    new Reference(sprintf('workflow.process.%s', $data['target']))
                ));
            } else {
                throw new \InvalidArgumentException(sprintf('Unknown type "%s", please use "step" or "process"', $data['type']));
            }
        }
    }

}
