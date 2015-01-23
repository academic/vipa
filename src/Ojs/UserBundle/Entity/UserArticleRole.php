<?php

namespace Ojs\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserArticleRole
 */
class UserArticleRole
{
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
    private $articleId;

    /**
     * @var integer
     */
    private $roleId;

    /**
     * @var \Ojs\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Ojs\JournalBundle\Entity\Article
     */
    private $article;

    /**
     * @var \Ojs\UserBundle\Entity\Role
     */
    private $role;


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
     * @return UserArticleRole
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
     * Set articleId
     *
     * @param integer $articleId
     * @return UserArticleRole
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;

        return $this;
    }

    /**
     * Get articleId
     *
     * @return integer
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * Set roleId
     *
     * @param integer $roleId
     * @return UserArticleRole
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Get roleId
     *
     * @return integer
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Set user
     *
     * @param \Ojs\UserBundle\Entity\User $user
     * @return UserArticleRole
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

    /**
     * Set article
     *
     * @param \Ojs\JournalBundle\Entity\Article $article
     * @return UserArticleRole
     */
    public function setArticle(\Ojs\JournalBundle\Entity\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \Ojs\JournalBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set role
     *
     * @param \Ojs\UserBundle\Entity\Role $role
     * @return UserArticleRole
     */
    public function setRole(\Ojs\UserBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \Ojs\UserBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }
}
