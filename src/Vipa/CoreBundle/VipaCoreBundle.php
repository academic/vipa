<?php

namespace Vipa\CoreBundle;

use Vipa\CoreBundle\DependencyInjection\Compiler\MailEventCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class VipaCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MailEventCompilerPass());
    }
}
