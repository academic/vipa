<?php

namespace Vipa\CoreBundle\Acl;

use Vipa\JournalBundle\Service\JournalService;
use Vipa\JournalBundle\Entity\Journal;
use Vipa\UserBundle\Entity\User;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\SecurityIdentityRetrievalStrategy as BaseSecurityIdentityRetrievalStrategy;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolver;
use Symfony\Component\Security\Core\Authentication\Token;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/**
 * Extending MutableAclProvider for JournalRoleSecurityIdentity support
 * Class SecurityIdentityRetrievalStrategy
 * @package Vipa\CoreBundle\Acl
 */
class SecurityIdentityRetrievalStrategy extends BaseSecurityIdentityRetrievalStrategy
{

    /**
     * @var RoleHierarchyInterface
     */
    private $roleHierarchy;

    /**
     * @var AuthenticationTrustResolver
     */
    private $authenticationTrustResolver;

    /** @var  JournalService */
    private $journalService;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        RoleHierarchyInterface $roleHierarchy,
        AuthenticationTrustResolver $authenticationTrustResolver,
        JournalService $journalService
    ) {
        $this->roleHierarchy = $roleHierarchy;
        $this->authenticationTrustResolver = $authenticationTrustResolver;
        $this->journalService = $journalService;
    }

    /**
     * {@inheritdoc}
     */
    public function getSecurityIdentities(Token\TokenInterface $token)
    {
        $sids = array();

        // add user security identity
        if (!$token instanceof Token\AnonymousToken) {
            try {
                $sids[] = UserSecurityIdentity::fromToken($token);
            } catch (\InvalidArgumentException $invalid) {
                // ignore, user has no user security identity
            }
        }

        // add all reachable roles
        foreach ($this->roleHierarchy->getReachableRoles($token->getRoles()) as $role) {
            $sids[] = new RoleSecurityIdentity($role);
        }
        // add journal roles

        $user = $token->getUser();
        try {
            $selectedJournal = $this->journalService->getSelectedJournal();
        } catch (\Exception $e) {
            $selectedJournal = false;
        }

        if ($user instanceof User && $selectedJournal instanceof Journal) {
            foreach ($user->getJournalRoles($selectedJournal) as $journalRoles) {
                $sids[] = new JournalRoleSecurityIdentity($journalRoles[0], $journalRoles[1]);
            }
        }

        // add built-in special roles
        if ($this->authenticationTrustResolver->isFullFledged($token)) {
            $sids[] = new RoleSecurityIdentity(AuthenticatedVoter::IS_AUTHENTICATED_FULLY);
            $sids[] = new RoleSecurityIdentity(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED);
            $sids[] = new RoleSecurityIdentity(AuthenticatedVoter::IS_AUTHENTICATED_ANONYMOUSLY);
        } elseif ($this->authenticationTrustResolver->isRememberMe($token)) {
            $sids[] = new RoleSecurityIdentity(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED);
            $sids[] = new RoleSecurityIdentity(AuthenticatedVoter::IS_AUTHENTICATED_ANONYMOUSLY);
        } elseif ($this->authenticationTrustResolver->isAnonymous($token)) {
            $sids[] = new RoleSecurityIdentity(AuthenticatedVoter::IS_AUTHENTICATED_ANONYMOUSLY);
        }

        return $sids;
    }
}
