<?php

namespace Ojs\CoreBundle;

use Ojs\CoreBundle\DependencyInjection\Compiler\MailEventCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OjsCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MailEventCompilerPass());
    }
}
