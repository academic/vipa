<?php

namespace Ojs\UserBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * Notification
 * @GRID\Source(columns="id,senderId,recipientId,entityId,entityName,isRead")
 */
class Notification implements Translatable
{
    use GenericEntityTrait;
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $senderId;

    /**
     * @var integer
     */
    private $recipientId;

    /**
     * @var integer
     */
    private $entityId;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $action;

    /**
     * @var boolean
     */
    private $isRead = false;

    /**
     * @var string
     */
    private $level = 1;

    /**
     * @var \Ojs\UserBundle\Entity\User
     */
    private $sender;

    /**
     * @var \Ojs\UserBundle\Entity\User
     */
    private $recipient;

    public function getSender()
    {
        return $this->sender;
    }

    /**
     *
     * @param  \Ojs\UserBundle\Entity\User         $sender
     * @return \Ojs\UserBundle\Entity\Notification
     */
    public function setSender(\Ojs\UserBundle\Entity\User $sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     *
     * @return \Ojs\UserBundle\Entity\User
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     *
     * @param  \Ojs\UserBundle\Entity\User         $recipient
     * @return \Ojs\UserBundle\Entity\Notification
     */
    public function setRecipient(\Ojs\UserBundle\Entity\User $recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

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
     * Set senderId
     *
     * @param  integer      $senderId
     * @return Notification
     */
    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;

        return $this;
    }

    /**
     * Get senderId
     *
     * @return integer
     */
    public function getSenderId()
    {
        return $this->senderId;
    }

    /**
     * Set recipientId
     *
     * @param  integer      $recipientId
     * @return Notification
     */
    public function setRecipientId($recipientId)
    {
        $this->recipientId = $recipientId;

        return $this;
    }

    /**
     * Get recipientId
     *
     * @return integer
     */
    public function getRecipientId()
    {
        return $this->recipientId;
    }

    /**
     * Set entityId
     *
     * @param  integer      $entityId
     * @return Notification
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId
     *
     * @return integer
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set entityName
     *
     * @param  string       $entityName
     * @return Notification
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;

        return $this;
    }

    /**
     * Get entityName
     *
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Set isRead
     *
     * @param  boolean      $isRead
     * @return Notification
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * Set level
     *
     * @param  string       $level
     * @return Notification
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set text
     *
     * @param  string       $text
     * @return Notification
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set action
     *
     * @param  string       $action
     * @return Notification
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Notification
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Notification
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }
}
