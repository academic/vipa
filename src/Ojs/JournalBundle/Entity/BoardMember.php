<?php

namespace Ojs\JournalBundle\Entity;

use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;
use Ojs\UserBundle\Entity\User;

/**
 * BoardMember
 */
class BoardMember implements Translatable
{
    use GenericEntityTrait;

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
     * @var Board
     */
    private $board;

    /**
     * @var User
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
     * @param  integer     $userId
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
     * @param  integer     $boardId
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
     * @param  integer     $seq
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
     * @param  Board       $board
     * @return BoardMember
     */
    public function setBoard(Board $board = null)
    {
        $this->board = $board;

        return $this;
    }

    /**
     * Get board
     *
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Set user
     *
     * @param  User        $user
     * @return BoardMember
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
