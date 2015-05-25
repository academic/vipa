<?php

namespace Ojs\SiteBundle\Acl;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Exception\InvalidDomainObjectException;
use Symfony\Component\Security\Acl\Model\ObjectIdentityInterface;
use Symfony\Component\Security\Acl\Model\ObjectIdentityRetrievalStrategyInterface;

class ObjectIdentityRetrievalStrategy implements ObjectIdentityRetrievalStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function getObjectIdentity($domainObject)
    {
        try {
            if ($domainObject instanceof ObjectIdentityInterface) {
                return $domainObject;
            }

            return ObjectIdentity::fromDomainObject($domainObject);
        } catch (InvalidDomainObjectException $failed) {
            return;
        }
    }
}
