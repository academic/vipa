<?php

namespace Ojs\ApiBundle\Tests;

use Ojs\CoreBundle\Tests\BaseTestSetup;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ApiBaseTestCase
 * @package Ojs\ApiBundle\Tests
 */
abstract class ApiBaseTestCase extends BaseTestSetup
{
    /**
     * @param array $parameters
     * @return array
     */
    public function getRouteParams($parameters = array())
    {
        return array_merge($parameters, $this->getApiKeyParams());
    }

    /**
     * @return array
     */
    public function getApiKeyParams()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
            ->from('OjsUserBundle:User', 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%ROLE_SUPER_ADMIN%')
            ->getQuery()
            ->getResult()
        ;
        $getAdminUsers = $qb->getQuery()->getResult();
        if(count($getAdminUsers) < 1){
            throw new NotFoundHttpException('Create an admin user');
        }
        /** @var User $getAdminUser */
        $getAdminUser = $getAdminUsers[0];
        if(empty($getAdminUser->getApiKey())){
            $getAdminUser->setApiKey($getAdminUser->generateApiKey());
            $this->em->persist($getAdminUser);
            $this->em->flush();
        }
        return [
            'apikey' => $getAdminUser->getApiKey()
        ];
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
