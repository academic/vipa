<?php

namespace Ojs\AdminBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Ojs\CoreBundle\Entity\DisplayTrait;
use Ojs\JournalBundle\Entity\Publisher;
use Ojs\UserBundle\Entity\User;

/**
 * PublisherManagers
 * @GRID\Source(columns="id, user.username, publisher.translations.name")
 */
class PublisherManagers
{
    use DisplayTrait;
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Publisher
     * @Grid\Column(field="publisher.translations.name",title="publisher")
     */
    private $publisher;

    /**
     * @var User
     * @Grid\Column(field="user.username", title="user")
     */
    private $user;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Publisher
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * @param Publisher $publisher
     * @return $this
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}
