<?php

namespace Vipa\InstallerBundle\Controller;

use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\UserBundle\Entity\Role;
use Vipa\UserBundle\Entity\User;
use Vipa\UserBundle\Form\Type\UserFirstType;
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
        $pwd = $this->get('kernel')->getRootDir();
        $consoleApp = $pwd.'/console';

        $query = shell_exec("php $consoleApp doctrine:schema:update --force");

        $user = new User();
        $data['schema_update'] = $query;
        $data['role_insert'] = $this->insertRoles();
        $data['user_form'] = $this->createForm(
            new UserFirstType(),
            $user,
            [
                'method' => 'POST',
                'action' => $this->get('router')->generate('vipa_installer_create_admin'),
            ]
        )->createView();

        return $this->render("VipaInstallerBundle:Default:setup.html.twig", $data);
    }

    private function insertRoles()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $roles = $this->container->getParameter('roles');
        $role_repo = $em->getRepository('VipaUserBundle:Role');
        $return = [];
        foreach ($roles as $role) {
            $new_role = new Role();
            $check = $role_repo->findOneBy(array('role' => $role['role']));
            if (!empty($check)) {
                continue;
            }
            $return[] = "Added :   {$role['role']}";
            $new_role->setName($role['desc']);
            $new_role->setRole($role['role']);
            $em->persist($new_role);
        }

        $em->flush();

        return implode("\n", $return);
    }

    public function createUserAction(Request $request)
    {
        $user = new User();
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(
            new UserFirstType(),
            $user,
            [
                'method' => 'POST',
                'action' => $this->get('router')->generate('vipa_installer_create_admin'),
            ]
        );
        $form->handleRequest($request);
        if ($form->isValid()) {
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);
            $user->setEnabled(true);
            $user->generateApiKey();

            $user->addRole('ROLE_ADMIN');

            $em->persist($user);
            $em->flush();
            $providerKey = 'main'; //  firewall name
            $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            return new RedirectResponse('/install/summary');
        }

        return new RedirectResponse($this->get('router')->generate('vipa_installer_setup'));
    }
}
