<?php

namespace Ojs\InstallerBundle\Controller;

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Form\UserFirstType;
use Ojs\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SetupController extends Controller
{

    public function setupAction()
    {
        $data = [];
        $data['data']['page'] = 'setup';
        /**
         * 1 - Run doctrine db schema
         * 2 - Show user form
         * 3 - Create user
         */
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $pwd = $this->get('kernel')->getRootDir();
        $consoleApp = $pwd . '/console';

        $query = shell_exec("php $consoleApp doctrine:schema:update --force");

        $user = new User();
        $data['schema_update'] = $query;
        $data['role_insert'] = $this->insertRoles();
        $data['user_form'] = $this->createForm(new UserFirstType(), $user, [
            'method' => 'POST',
            'action' => $this->get('router')->generate('ojs_installer_create_admin')
        ])->createView();
        return $this->render("OjsInstallerBundle:Default:setup.html.twig", $data);
    }

    public function createUserAction(Request $request)
    {
        $user = new User();
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new UserFirstType(), $user, [
            'method' => 'POST',
            'action' => $this->get('router')->generate('ojs_installer_create_admin')
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);
            $user->setStatus(1);
            $user->generateApiKey();
            $role_repo = $em->getRepository('OjsUserBundle:Role');
            $role_sys_admin = $role_repo->findOneByRole('ROLE_SUPER_ADMIN');
            $role_admin = $role_repo->findOneByRole('ROLE_USER');
            $role_editor = $role_repo->findOneByRole('ROLE_EDITOR');
            $role_reviewer = $role_repo->findOneByRole('ROLE_REVIEWER');

            $user->addRole($role_sys_admin);
            $user->addRole($role_admin);
            $user->addRole($role_editor);
            $user->addRole($role_reviewer);

            $em->persist($user);
            $em->flush();
            $providerKey = 'main'; //  firewall name
            $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
            $this->get('security.context')->setToken($token);

            return new RedirectResponse('/install/summary');
        }
        return new RedirectResponse($this->get('router')->generate('ojs_installer_setup'));
    }

    public function insertRoles()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $roles = $this->container->getParameter('roles');
        $role_repo = $em->getRepository('OjsUserBundle:Role');
        $return = [];
        foreach ($roles as $role) {
            $new_role = new Role();
            $check = $role_repo->findOneByRole($role['role']);
            if (!empty($check))
                continue;
            $return[] = "Added :   {$role['role']}";
            $new_role->setName($role['desc']);
            $new_role->setRole($role['role']);
            $new_role->setIsSystemRole($role['isSystemRole']);
            $em->persist($new_role);
        }

        $em->flush();
        return join("\n", $return);
    }
}
