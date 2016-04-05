<?php

namespace Ojs\JournalBundle\Entity;

use Gedmo\Translatable\Translatable;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Ojs\UserBundle\Entity\User;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * BoardMember
 * @GRID\Source(columns="id, user.username, seq, showMail")
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
     * @Grid\Column(field="board.name", title="board")
     */
    private $board;

    /**
     * @var User
     * @Grid\Column(field="user.username", title="user")
     */
    private $user;

    /**
     * @var bool
     * @Grid\Column(title="show.mail")
     */
    private $showMail = true;

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
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
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
     * Get boardId
     *
     * @return integer
     */
    public function getBoardId()
    {
        return $this->boardId;
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
     * Get seq
     *
     * @return integer
     */
    public function getSeq()
    {
        return $this->seq;
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
     * Get board
     *
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
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
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
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
     * @return boolean
     */
    public function isShowMail()
    {
        return $this->showMail;
    }

    /**
     * @param boolean $showMail
     *
     * @return $this
     */
    public function setShowMail($showMail)
    {
        $this->showMail = $showMail;

        return $this;
    }

    public function __toString()
    {
        return (string)$this->user;
    }
}
