<?php

namespace Vipa\UserBundle\OAuth\ResourceOwner;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\GenericOAuth2ResourceOwner as BaseClass;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Vipa\CoreBundle\Service\OrcidService;

class GenericOAuth2ResourceOwner extends BaseClass
{
    /**
     * Override for Orcid
     *
     * {@inheritDoc}
     */
    public function getUserInformation(array $accessToken, array $extraParameters = array())
    {
        if(!array_key_exists('orcid', $accessToken)) {
            return parent::getUserInformation($accessToken, $extraParameters);
        }

        $orcidService = new OrcidService();
        $bio = $orcidService->getBio($accessToken["orcid"]);

        $response = $this->getUserResponse();
        $response->setResponse($bio);

        $response->setResourceOwner($this);
        $response->setOAuthToken(new OAuthToken($accessToken));

        return $response;
    }
}
