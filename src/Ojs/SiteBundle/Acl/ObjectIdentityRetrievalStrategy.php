<?php

namespace Ojs\SiteBundle\Acl;


use Symfony\Component\Security\Core\Util\ClassUtils;
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
            if(method_exists($domainObject, 'getId') && is_null($domainObject->getId())) {
                return new ObjectIdentity('CLASS', ClassUtils::getRealClass($domainObject));
            }

            return ObjectIdentity::fromDomainObject($domainObject);
        } catch (InvalidDomainObjectException $failed) {
            return;
        }
    }
}
