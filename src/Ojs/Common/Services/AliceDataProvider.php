<?php

namespace Ojs\Common\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

/*
* Some custom providers for alice data fixtures 
*/
class AliceDataProvider
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

    /**
     * get default institution record
     * @return \Ojs\JournalBundle\Entity\Institution
     */
    public function defaultInstitutionSlug($redirect = true)
    { 
        return $this->container->getParameter('defaultInstitutionSlug'); 
    }

    /**
     * get default institution record
     * @return \Ojs\JournalBundle\Entity\Institution
     */
    public function systemEmail($redirect = true)
    { 
        return $this->container->getParameter('system_email'); 
    }


}
