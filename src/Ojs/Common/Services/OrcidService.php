<?php
namespace Ojs\Common\Services;

use Monolog\Logger;

class OrcidService
{
    const PUBLIC_API = "https://pub.orcid.org";
    const MEMBER_API = "https://orcid.org";
    const SANDBOX_API = "https://api.sandbox.orcid.org";
    const TOKEN_PATH = "oauth/token";
    const AUTHORIZATION_PATH = "oauth/authorize";

    /**
     * @var Logger
     */
    private $logger;
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
    /** @var  string */
    private $access_token;

    /**
     * @param $orcid
     * @param Logger $logger
     */
    public function __construct($orcid, Logger $logger)
    {
        $this->setClientId($orcid['client_id']);
        $this->setClientSecret($orcid['client_secret']);
        $this->setRedirectUri($orcid['redirect_uri']);
        $this->setSandbox($orcid['sandbox']);
        $this->logger = $logger;
    }

    /**
     * Get OrcidLogin Url
     * @return string
     */
    public function loginUrl()
    {
        $state = bin2hex(openssl_random_pseudo_bytes(16));
        if ($this->isSandbox()) {
            $url = self::SANDBOX_API
                .DIRECTORY_SEPARATOR
                .self::AUTHORIZATION_PATH
                .DIRECTORY_SEPARATOR
                .'?'
                .http_build_query(
                    [
                        'response_type' => 'code',
                        'client_id' => $this->getClientId(),
                        'redirect_uri' => $this->getRedirectUri(),
                        'scope' => '/authenticate',
                        'state' => $state,
                    ]
                );
        } else {
            $url = self::MEMBER_API
                .DIRECTORY_SEPARATOR
                .self::AUTHORIZATION_PATH
                .DIRECTORY_SEPARATOR
                .'?'
                .http_build_query(
                    [
                        'response_type' => 'code',
                        'client_id' => $this->getClientId(),
                        'redirect_uri' => $this->getRedirectUri(),
                        'scope' => '/authenticate',
                    ]
                );
        }

        return $url;
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

    public function authorize($code)
    {
        $auth = $this->post(self::TOKEN_PATH, $code, 'authorization_code');

        return $auth;
    }

    private function post($path, $code, $grant_type, $fields = [])
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => self::PUBLIC_API.DIRECTORY_SEPARATOR.$path,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => ['Accept: application/json'],
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query(
                    array_merge(
                        [
                            'code' => $code,
                            'grant_type' => $grant_type,
                            'client_id' => $this->getClientId(),
                            'client_secret' => $this->getClientSecret(),
                            'redirect_uri' => $this->getRedirectUri(),
                        ],
                        $fields
                    )
                ),
            ]
        );
        $result = curl_exec($curl);
        $this->logger->addDebug('#Orcid Debug', [$result]);

        return json_decode($result);
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

    public function getBio($user_id, $access_token)
    {
        $this->setAccessToken($access_token);
        $data = $this->get($user_id.'/orcid-profile');

        return $data;
    }

    private function get($path)
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => self::PUBLIC_API.DIRECTORY_SEPARATOR.$path,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Authorization: Bearer '.$this->getAccessToken(),
                ],
            ]
        );
        //https://api.sandbox.orcid.org/v1.1/0000-0003-1495-7122/orcid-profile
        $result = curl_exec($curl);

        return json_decode($result);
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @param string $access_token
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
    }
}
