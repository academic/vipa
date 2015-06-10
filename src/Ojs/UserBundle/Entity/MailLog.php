<?php

namespace Ojs\UserBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * MailLog
 * @GRID\Source(columns="id,mailObject,recipientEmail")
 */
class MailLog implements Translatable
{
    use GenericEntityTrait;
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
     * @param  string  $mailObject
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
     * @param  string  $recipientEmail
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
