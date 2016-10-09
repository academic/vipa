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

    public function boot()
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.default_entity_manager');

        $conf = $em->getConfiguration();

        $conf->addFilter(
            'catalogfilter',
            'Ojs\CoreBundle\Filter\CatalogFilter'
        );

        $em->getFilters()->enable('catalogfilter')->setCatalogs($this->container->getParameter('catalogs'));
    }
}
