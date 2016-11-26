<?php

namespace Ojs\CoreBundle\Service;

use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

/**
 * Class DynamicDomainSessionStorage
 * @package Ojs\CoreBundle\Service
 */
class DynamicDomainSessionStorage extends NativeSessionStorage
{
    /**
     * setOptions.
     *
     * {@inheritDoc}
     */
    public function setOptions(array $options)
    {
        if(isset($_SERVER['HTTP_HOST'])){

            $options["cookie_domain"] = '.'.$_SERVER['HTTP_HOST'];
        }

        return parent::setOptions($options);
    }
}