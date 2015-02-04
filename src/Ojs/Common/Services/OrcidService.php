<?php
/**
 * Created by PhpStorm.
 * User: emreyilmaz
 * Date: 4.02.15
 * Time: 23:44
 */

namespace Ojs\Common\Services;


use Symfony\Component\DependencyInjection\ContainerInterface;

class OrcidService
{
    const PUBLIC_API = "http://pub.orcid.org";
    const MEMBER_API = "https://orcid.org";
    const SANDBOX_API = "https://api.sandbox.orcid.org";
    const TOKEN_PATH = "oauth/token";
    const AUTHORIZATION_PATH = "oauth/authorize";

    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
        $parameters = $this->getContainer()->getParameter('orcid');
        $this->setClientId($parameters['client_id']);
        $this->setClientSecret($parameters['client_secret']);
        $this->setRedirectUri($parameters['redirect_uri']);
        $this->setSandbox($parameters['sandbox']);
    }

    /**
     * @var string Orcid Client ID
     */
    private $client_id;
    /**
     * @var string Orcid Client Secret
     */
    private $client_secret;
    /**
     * @var bool
     */
    private $sandbox = true;
    /**
     * @var string
     */
    private $redirect_uri;

    /** @var  ContainerInterface */
    private $container;

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * @param string $client_id
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->client_secret;
    }

    /**
     * @param string $client_secret
     */
    public function setClientSecret($client_secret)
    {
        $this->client_secret = $client_secret;
    }

    /**
     * @return boolean
     */
    public function isSandbox()
    {
        return $this->sandbox;
    }

    /**
     * @param boolean $sandbox
     */
    public function setSandbox($sandbox)
    {
        $this->sandbox = $sandbox;
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }

    /**
     * @param string $redirect_uri
     */
    public function setRedirectUri($redirect_uri)
    {
        $this->redirect_uri = $redirect_uri;
    }

    /**
     * Get OrcidLogin Url
     * @return string
     */
    public function loginUrl()
    {
        $state = bin2hex(openssl_random_pseudo_bytes(16));
        setcookie('state', $state, time() + 3600, null, null, false, true);

        if ($this->isSandbox()) {
            $url = self::SANDBOX_API
                . DIRECTORY_SEPARATOR
                . self::AUTHORIZATION_PATH
                . DIRECTORY_SEPARATOR
                . '?'
                . http_build_query([
                    'response_type' => 'code',
                    'client_id' => $this->getClientId(),
                    'redirect_uri' => $this->getRedirectUri(),
                    'scope' => '/authenticate',
                    'state' => $state
                ]);
        } else {
            $url = self::MEMBER_API
                . DIRECTORY_SEPARATOR
                . self::AUTHORIZATION_PATH
                . DIRECTORY_SEPARATOR
                . '?'
                . http_build_query([
                    'response_type' => 'code',
                    'client_id' => $this->getClientId(),
                    'redirect_uri' => $this->getRedirectUri(),
                    'scope' => '/authenticate',
                ]);
        }
        return $url;
    }


    private function post($path, $code, $grant_type, $fields = [])
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->isSandbox() ? self::SANDBOX_API : self::MEMBER_API . DIRECTORY_SEPARATOR . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => array_merge([
                'code' => $code,
                'grant_type' => $grant_type,
                'client_id' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
                'redirect_uri' => $this->getRedirectUri()
            ], $fields)
        ]);
        $result = curl_exec($curl);
        return json_decode($result);
    }


}