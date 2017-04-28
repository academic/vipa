<?php

namespace Vipa\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class MailEventCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('vipa_core.mail.event_chain')) {
            return;
        }

        $definition = $container->findDefinition(
            'vipa_core.mail.event_chain'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'vipa.mail.event'
        );
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addMailEvent',
                array(new Reference($id))
            );
        }
    }
}