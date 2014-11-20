<?php
/**
 * User: aybarscengaver
 * Date: 20.11.14
 * Time: 23:21
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

namespace Ojs\UserBundle\Security;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EmailUserProvider implements UserProviderInterface{
    /** @var EntityManager  */
    private $e;
    private $c;
    public function __construct(ContainerInterface $container){
        $this->c = $container;
        $this->e = $container->get('doctrine.orm.entity_manager');
    }

    public function find($id)
    {
        $q = $this
            ->e
            ->createQueryBuilder()
            ->select('u, r')
            ->from('OjsUserBundle:User','u')
            ->leftJoin('u.roles', 'r')
            ->where('u.id = :id')
            ->setParameter('id',$id)
            ->getQuery();
        return $q->getSingleResult();
    }
    public function loadUserByUsername($username)
    {
        $q = $this
            ->e
            ->createQueryBuilder()
            ->select('u, r')
            ->from('OjsUserBundle:User','u')
            ->leftJoin('u.roles', 'r')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery();
        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            $message = sprintf('Unable to find an active admin OjsUserBundle:User object identified by "%s".', $username);
            throw new UsernameNotFoundException($message, 0, $e);
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', $class)
            );
        }

        return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return 'Ojs\UserBundle\Entity\User' === $class || is_subclass_of($class, 'Ojs\UserBundle\Entity\User');
    }

}