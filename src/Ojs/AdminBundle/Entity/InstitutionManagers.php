<?php

namespace Ojs\AdminBundle\Entity;

use Ojs\JournalBundle\Entity\Institution;
use Ojs\UserBundle\Entity\User;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * InstitutionManagers
 * @GRID\Source(columns="id, user.username, institution.name")
 */
class InstitutionManagers
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Institution
     * @Grid\Column(field="institution.name",title="institution")
     */
    private $institution;

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
     * @return Institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * @param Institution $institution
     * @return $this
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;
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
