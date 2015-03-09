<?php

namespace Ojs\Common\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Some system roles can't be loaded directly from alice yml files.
 * We need this custom data provider for ojs:install:sample
 */
class RolesFakerProvider
{

    protected $container;

    /**
     * 
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function defaultUserRole()
    {
        $role = $this->container->get('doctrine')
                ->getManager()->getRepository('OjsUserBundle:Role')
                ->findBy(array('role' => 'ROLE_USER'));
        return $role;
    }

}
