<?php

namespace Vipa\CoreBundle\Service;

/*
* Some custom providers for alice data fixtures
*/
class AliceDataProvider
{

    protected $defaultPublisherSlug;

    protected $systemEmail;

    /**
     * @param $defaultPublisherSlug
     * @param $systemEmail
     */
    public function __construct($defaultPublisherSlug, $systemEmail)
    {
        $this->defaultPublisherSlug = $defaultPublisherSlug;
        $this->systemEmail = $systemEmail;
    }

    /**
     * get default publisher record
     * @return string
     */
    public function defaultPublisherSlug()
    {
        return $this->defaultPublisherSlug;
    }

    /**
     * @return string
     */
    public function systemEmail()
    {
        return $this->systemEmail;
    }
}
