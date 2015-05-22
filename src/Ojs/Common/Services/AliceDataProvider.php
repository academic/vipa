<?php

namespace Ojs\Common\Services;

/*
* Some custom providers for alice data fixtures
*/
class AliceDataProvider
{

    protected $defaultInstitutionSlug;

    protected $systemEmail;

    /**
     * @param $defaultInstitutionSlug
     * @param $systemEmail
     */
    public function __construct($defaultInstitutionSlug, $systemEmail)
    {
        $this->defaultInstitutionSlug = $defaultInstitutionSlug;
        $this->systemEmail = $systemEmail;
    }

    /**
     * get default institution record
     * @return string
     */
    public function defaultInstitutionSlug()
    {
        return $this->$defaultInstitutionSlug;
    }

    /**
     * @return string
     */
    public function systemEmail()
    {
        return $this->systemEmail;
    }
}
