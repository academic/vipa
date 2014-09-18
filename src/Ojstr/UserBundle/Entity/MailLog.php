<?php

namespace Ojstr\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ojstr\Common\Entity\GenericExtendedEntity;

/**
 * MailLog
 */
class MailLog extends GenericExtendedEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $mailObject;

    /**
     * @var string
     */
    private $recipientEmail;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set mailObject
     * @param string $mailObject
     * @return MailLog
     */
    public function setMailObject($mailObject)
    {
        $this->mailObject = $mailObject;
        return $this;
    }

    /**
     * Get mailObject
     * @return string
     */
    public function getMailObject()
    {
        return $this->mailObject;
    }

    /**
     * Set recipientEmail
     *
     * @param string $recipientEmail
     * @return MailLog
     */
    public function setRecipientEmail($recipientEmail)
    {
        $this->recipientEmail = $recipientEmail;

        return $this;
    }

    /**
     * Get recipientEmail
     * @return string
     */
    public function getRecipientEmail()
    {
        return $this->recipientEmail;
    }
}
