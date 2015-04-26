<?php

namespace Ojs\JournalBundle\Entity;

use Ojs\Common\Entity\GenericExtendedEntity;

/**
 * BoardMember
 */
class BoardMember extends GenericExtendedEntity {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $boardId;

    /**
     * @var integer
     */
    private $seq;

    /**
     * @var \Ojs\JournalBundle\Entity\Board
     */
    private $board;

    /**
     * @var \Ojs\UserBundle\Entity\User
     */
    private $user;

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
     * Set userId
     *
     * @param integer $userId
     * @return BoardMember
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set boardId
     *
     * @param integer $boardId
     * @return BoardMember
     */
    public function setBoardId($boardId)
    {
        $this->boardId = $boardId;

        return $this;
    }

    /**
     * Get boardId
     *
     * @return integer 
     */
    public function getBoardId()
    {
        return $this->boardId;
    }

    /**
     * Set seq
     *
     * @param integer $seq
     * @return BoardMember
     */
    public function setSeq($seq)
    {
        $this->seq = $seq;

        return $this;
    }

    /**
     * Get seq
     *
     * @return integer 
     */
    public function getSeq()
    {
        return $this->seq;
    }

    /**
     * Set board
     *
     * @param \Ojs\JournalBundle\Entity\Board $board
     * @return BoardMember
     */
    public function setBoard(\Ojs\JournalBundle\Entity\Board $board = null)
    {
        $this->board = $board;

        return $this;
    }

    /**
     * Get board
     *
     * @return \Ojs\JournalBundle\Entity\Board 
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Set user
     *
     * @param \Ojs\UserBundle\Entity\User $user
     * @return BoardMember
     */
    public function setUser(\Ojs\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Ojs\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

}
