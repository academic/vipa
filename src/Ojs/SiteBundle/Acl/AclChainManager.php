<?php

namespace Ojs\SiteBundle\Acl;

use Problematic\AclManagerBundle\Domain\AclManager;

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
        if (!is_null($this->_onClass)) {
            $this->addPermission($this->_onClass, $this->_field,  $this->_mask, $this->_to, 'class', $replace);
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
}
