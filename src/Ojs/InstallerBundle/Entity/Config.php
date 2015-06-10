<?php

namespace Ojs\InstallerBundle\Entity;

/**
 * Config
 */
class Config
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $database_driver;

    /**
     * @var string
     */
    private $database_host;

    /**
     * @var string
     */
    private $database_port;

    /**
     * @var string
     */
    private $database_name;

    /**
     * @var string
     */
    private $database_user;

    /**
     * @var string
     */
    private $database_password;

    /**
     * @var string
     */
    private $system_email;

    /**
     * @var string
     */
    private $mailer_transport;

    /**
     * @var string
     */
    private $mailer_host;

    /**
     * @var string
     */
    private $mailer_user;

    /**
     * @var string
     */
    private $mailer_password;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var string
     */
    private $base_host;

    /**
     * @var string
     */
    private $post_types;

    /**
     * @var string
     */
    private $elasticsearch_host;

    /**
     * @var string
     */
    private $mongodb_host;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function toArray()
    {
        $posttypes = json_decode($this->getPostTypes(), true);
        if (!is_array($posttypes)) {
            $posttypes = json_decode($posttypes, true);
        } //@todo i can't understand why its need double decode.

        return [
            'parameters' => [
                'database_driver' => $this->getDatabaseDriver(),
                'database_host' => $this->getDatabaseHost(),
                'database_port' => $this->getDatabasePort(),
                'database_name' => $this->getDatabaseName(),
                'database_user' => $this->getDatabaseUser(),
                'database_password' => $this->getDatabasePassword(),
                'system_email' => $this->getSystemEmail(),
                'mailer_transport' => $this->getMailerTransport(),
                'mailer_host' => $this->getMailerHost(),
                'mailer_user' => $this->getMailerUser(),
                'mailer_password' => $this->getMailerPassword(),
                'locale' => $this->getLocale(),
                'secret' => $this->getSecret(),
                'base_host' => $this->getBaseHost(),
                'post_types' => $posttypes ? $posttypes : [],
                'elasticsearch_host' => $this->getElasticsearchHost(),
                'mongodb_host' => $this->getMongodbHost(),
            ],
        ];
    }

    /**
     * Get post_types
     *
     * @return string
     */
    public function getPostTypes()
    {
        return $this->post_types;
    }

    /**
     * Set post_types
     *
     * @param  string $postTypes
     * @return Config
     */
    public function setPostTypes($postTypes)
    {
        $this->post_types = json_encode($postTypes);

        return $this;
    }

    /**
     * Get database_driver
     *
     * @return string
     */
    public function getDatabaseDriver()
    {
        return $this->database_driver;
    }

    /**
     * Set database_driver
     *
     * @param  string $databaseDriver
     * @return Config
     */
    public function setDatabaseDriver($databaseDriver)
    {
        $this->database_driver = $databaseDriver;

        return $this;
    }

    /**
     * Get database_host
     *
     * @return string
     */
    public function getDatabaseHost()
    {
        return $this->database_host;
    }

    /**
     * Set database_host
     *
     * @param  string $databaseHost
     * @return Config
     */
    public function setDatabaseHost($databaseHost)
    {
        $this->database_host = $databaseHost;

        return $this;
    }

    /**
     * Get database_port
     *
     * @return string
     */
    public function getDatabasePort()
    {
        return $this->database_port;
    }

    /**
     * Set database_port
     *
     * @param  string $databasePort
     * @return Config
     */
    public function setDatabasePort($databasePort)
    {
        $this->database_port = $databasePort;

        return $this;
    }

    /**
     * Get database_name
     *
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->database_name;
    }

    /**
     * Set database_name
     *
     * @param  string $databaseName
     * @return Config
     */
    public function setDatabaseName($databaseName)
    {
        $this->database_name = $databaseName;

        return $this;
    }

    /**
     * Get database_user
     *
     * @return string
     */
    public function getDatabaseUser()
    {
        return $this->database_user;
    }

    /**
     * Set database_user
     *
     * @param  string $databaseUser
     * @return Config
     */
    public function setDatabaseUser($databaseUser)
    {
        $this->database_user = $databaseUser;

        return $this;
    }

    /**
     * Get database_password
     *
     * @return string
     */
    public function getDatabasePassword()
    {
        return $this->database_password;
    }

    /**
     * Set database_password
     *
     * @param  string $databasePassword
     * @return Config
     */
    public function setDatabasePassword($databasePassword)
    {
        $this->database_password = $databasePassword;

        return $this;
    }

    /**
     * Get system_email
     *
     * @return string
     */
    public function getSystemEmail()
    {
        return $this->system_email;
    }

    /**
     * Set system_email
     *
     * @param  string $systemEmail
     * @return Config
     */
    public function setSystemEmail($systemEmail)
    {
        $this->system_email = $systemEmail;

        return $this;
    }

    /**
     * Get mailer_transport
     *
     * @return string
     */
    public function getMailerTransport()
    {
        return $this->mailer_transport;
    }

    /**
     * Set mailer_transport
     *
     * @param  string $mailerTransport
     * @return Config
     */
    public function setMailerTransport($mailerTransport)
    {
        $this->mailer_transport = $mailerTransport;

        return $this;
    }

    /**
     * Get mailer_host
     *
     * @return string
     */
    public function getMailerHost()
    {
        return $this->mailer_host;
    }

    /**
     * Set mailer_host
     *
     * @param  string $mailerHost
     * @return Config
     */
    public function setMailerHost($mailerHost)
    {
        $this->mailer_host = $mailerHost;

        return $this;
    }

    /**
     * Get mailer_user
     *
     * @return string
     */
    public function getMailerUser()
    {
        return $this->mailer_user;
    }

    /**
     * Set mailer_user
     *
     * @param  string $mailerUser
     * @return Config
     */
    public function setMailerUser($mailerUser)
    {
        $this->mailer_user = $mailerUser;

        return $this;
    }

    /**
     * Get mailer_password
     *
     * @return string
     */
    public function getMailerPassword()
    {
        return $this->mailer_password;
    }

    /**
     * Set mailer_password
     *
     * @param  string $mailerPassword
     * @return Config
     */
    public function setMailerPassword($mailerPassword)
    {
        $this->mailer_password = $mailerPassword;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set locale
     *
     * @param  string $locale
     * @return Config
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get secret
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set secret
     *
     * @param  string $secret
     * @return Config
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Get base_host
     *
     * @return string
     */
    public function getBaseHost()
    {
        return $this->base_host;
    }

    /**
     * Set base_host
     *
     * @param  string $baseHost
     * @return Config
     */
    public function setBaseHost($baseHost)
    {
        $this->base_host = $baseHost;

        return $this;
    }

    /**
     * Get elasticsearch_host
     *
     * @return string
     */
    public function getElasticsearchHost()
    {
        return $this->elasticsearch_host;
    }

    /**
     * Set elasticsearch_host
     *
     * @param  string $elasticsearchHost
     * @return Config
     */
    public function setElasticsearchHost($elasticsearchHost)
    {
        $this->elasticsearch_host = $elasticsearchHost;

        return $this;
    }

    /**
     * Get mongodb_host
     *
     * @return string
     */
    public function getMongodbHost()
    {
        return $this->mongodb_host;
    }

    /**
     * Set mongodb_host
     *
     * @param  string $mongodbHost
     * @return Config
     */
    public function setMongodbHost($mongodbHost)
    {
        $this->mongodb_host = $mongodbHost;

        return $this;
    }
}
