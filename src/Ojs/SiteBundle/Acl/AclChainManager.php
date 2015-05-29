<?php

namespace Ojs\SiteBundle\Acl;

use Ojs\UserBundle\Entity\UserJournalRole;
use Problematic\AclManagerBundle\Domain\AclManager;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Model\ObjectIdentityInterface;
use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AclChainManager extends AclManager
{

    private $_to = null;

    private $_on = null;

    private $_onClass = null;

    private $_field = null;

    private $_mask = null;

    /**
     * @param  null  $securityIdentity
     * @return $this
     */
    public function to($securityIdentity = null)
    {
        $this->_to = $securityIdentity;

        return $this;
    }

    /**
     * @param $domainObject
     * @return $this
     */
    public function on($domainObject)
    {
        if (is_string($domainObject)) {
            return $this->onClass(new ObjectIdentity('CLASS', $domainObject));
        }
        $this->_onClass = null;
        $this->_on = $domainObject;

        return $this;
    }

    /**
     * @param $domainObject
     * @return $this
     */
    public function onClass($domainObject)
    {
        $this->_on = null;
        $this->_onClass = $domainObject;

        return $this;
    }

    /**
     * @param $field
     * @return $this
     */
    public function field($field)
    {
        $this->_field = $field;

        return $this;
    }

    /**
     * @param $mask
     * @return $this
     */
    public function permit($mask)
    {
        $this->_mask = $mask;

        return $this;
    }

    /**
     * @param  bool  $replace
     * @return $this
     */
    public function save($replace = false)
    {
        if (!is_null($this->_onClass) || ($this->_on instanceof ObjectIdentityInterface && $this->_on->getIdentifier() === 'CLASS')) {
            $this->addPermission($this->_onClass, $this->_field, $this->_mask, $this->_to, 'class', $replace);
        } else {
            $this->addPermission($this->_on, $this->_field,  $this->_mask, $this->_to, 'object', $replace);
        }

        $this->_to = $this->_on = $this->_onClass = $this->_field = $this->_mask = null;

        return $this;
    }

    /**
     * @return $this
     */
    public function revoke()
    {
        if (!is_null($this->_onClass)) {
            $this->revokeAllPermissions($this->_onClass, $this->_field, $this->_to, 'class');
        } else {
            $this->revokeAllPermissions($this->_onClass, $this->_field, $this->_to, 'object');
        }

        $this->_to = $this->_on = $this->_onClass = $this->_field = $this->_mask = null;

        return $this;
    }

    protected function doCreateSecurityIdentity($identity)
    {
        if (
            !$identity instanceof UserInterface &&
            !$identity instanceof TokenInterface &&
            !$identity instanceof UserJournalRole &&
            !$identity instanceof RoleInterface &&
            !is_string($identity)) {
            throw new \InvalidArgumentException(sprintf('$identity must implement one of: UserInterface, '.
                'TokenInterface, RoleInterface, UserJournalRole (%s given)', get_class($identity)));
        }

        $securityIdentity = null;
        if ($identity instanceof UserInterface) {
            $securityIdentity = UserSecurityIdentity::fromAccount($identity);
        } elseif ($identity instanceof TokenInterface) {
            $securityIdentity = UserSecurityIdentity::fromToken($identity);
        } elseif ($identity instanceof UserJournalRole) {
            $securityIdentity = JournalRoleSecurityIdentity::fromUserJournalRole($identity);
        } elseif ($identity instanceof RoleInterface || is_string($identity)) {
            $securityIdentity = new RoleSecurityIdentity($identity);
        }

        if (!$securityIdentity instanceof SecurityIdentityInterface) {
            throw new \InvalidArgumentException('Couldn\'t create a valid SecurityIdentity with the provided identity information');
        }

        return $securityIdentity;
    }
}
