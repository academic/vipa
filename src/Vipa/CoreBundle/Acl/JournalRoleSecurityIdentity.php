<?php

namespace Vipa\CoreBundle\Acl;

use Vipa\JournalBundle\Entity\Journal;
use Vipa\UserBundle\Entity\Role;
use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;

final class JournalRoleSecurityIdentity implements SecurityIdentityInterface
{
    /**
     * @var string
     */
    private $role;

    /**
     * @var integer
     */
    private $journal;

    /**
     * @param $journal
     * @param $role
     */
    public function __construct($journal = null, $role = null)
    {
        if (empty($journal)) {
            throw new \InvalidArgumentException('$journal must not be empty.');
        }
        if (empty($role)) {
            throw new \InvalidArgumentException('$role must not be empty.');
        }
        if ($journal instanceof Journal) {
            $journal = $journal->getId();
        }
        if ($role instanceof Role) {
            $role = (string) $role;
        }

        $this->journal = $journal;
        $this->role = $role;
    }

    public static function determine($securityIdentifier)
    {
        return (substr($securityIdentifier, 0, 11) === 'JournalRole');
    }

    public static function fromIdentifier($securityIdentifier)
    {
        list($role, $journal) = explode('-', substr($securityIdentifier, 12));

        return new self($journal, $role);
    }

    public function getIdentifier()
    {
        return 'JournalRole-'.$this->role.'-'.$this->journal;
    }

    /**
     * {@inheritdoc}
     */
    public function equals(SecurityIdentityInterface $sid)
    {
        if (!$sid instanceof JournalRoleSecurityIdentity) {
            return false;
        }

        return ($this->role === $sid->getRole() && (int)$this->journal === (int)$sid->getJournal());
    }

    /**
     * Returns the role name.
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Returns the journal ID.
     *
     * @return string
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Returns a textual representation of this security identity.
     *
     * This is solely used for debugging purposes, not to make an equality decision.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('JournalRoleSecurityIdentity-%s-%s', $this->role, $this->journal);
    }
}
