<?php

namespace Ojs\SiteBundle\Acl;

use Symfony\Component\Security\Acl\Dbal\MutableAclProvider as BaseMutableAclProvider;
use Symfony\Component\Security\Acl\Domain;
use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;

/**
 * Extending MutableAclProvider for JournalRoleSecurityIdentity support
 * Class MutableAclProvider
 * @package Ojs\SiteBundle\Acl
 */
class MutableAclProvider extends BaseMutableAclProvider
{

    /**
     * @inheritdoc
     */
    protected function getInsertSecurityIdentitySql(SecurityIdentityInterface $sid)
    {
        if ($sid instanceof Domain\UserSecurityIdentity) {
            $identifier = $sid->getClass().'-'.$sid->getUsername();
            $username = true;
        } elseif ($sid instanceof Domain\RoleSecurityIdentity) {
            $identifier = $sid->getRole();
            $username = false;
        } elseif ($sid instanceof JournalRoleSecurityIdentity) {
            $identifier = (string) $sid;
            $username = false;
        } else {
            throw new \InvalidArgumentException('$sid must either be an instance of UserSecurityIdentity, JournalRoleSecurityIdentity or RoleSecurityIdentity.');
        }

        return sprintf(
            'INSERT INTO %s (identifier, username) VALUES (%s, %s)',
            $this->options['sid_table_name'],
            $this->connection->quote($identifier),
            $this->connection->getDatabasePlatform()->convertBooleans($username)
        );
    }

    /**
     * @inheritdoc
     */
    protected function getSelectSecurityIdentityIdSql(SecurityIdentityInterface $sid)
    {
        if ($sid instanceof Domain\UserSecurityIdentity) {
            $identifier = $sid->getClass().'-'.$sid->getUsername();
            $username = true;
        } elseif ($sid instanceof Domain\RoleSecurityIdentity) {
            $identifier = $sid->getRole();
            $username = false;
        } elseif ($sid instanceof JournalRoleSecurityIdentity) {
            $identifier = (string) $sid;
            $username = false;
        } else {
            throw new \InvalidArgumentException('$sid must either be an instance of UserSecurityIdentity, JournalRoleSecurityIdentity or RoleSecurityIdentity.');
        }

        return sprintf(
            'SELECT id FROM %s WHERE identifier = %s AND username = %s',
            $this->options['sid_table_name'],
            $this->connection->quote($identifier),
            $this->connection->getDatabasePlatform()->convertBooleans($username)
        );
    }
}
