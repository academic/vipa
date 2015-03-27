<?php

namespace Ojs\Common\Services;

use Ojs\UserBundle\Entity\User;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserListener {

    protected $container;
    protected $session;
    protected $router;

    public function __construct(ContainerInterface $container, $router)
    { // this is @service_container
        $this->container = $container;
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $this->session = $request->getSession();

        if ($event->isMasterRequest()) {
            $this->loadJournals();
            $this->loadJournalRoles();
            $this->loadClientUsers();
            $check = $this->redirectUnconfirmed();
            $routeName = $this->container->get('request')->get('_route');
            if ($check && !in_array($routeName, array('email_confirm', 'confirm_email_warning', 'logout'))) {
                $event->setResponse(new RedirectResponse($check, 302));
            }
        }
        // fill journal roles
    }

    /**
     * get user's roles for selected journal and save to userJournalRoles session key
     * @return void
     */
    public function loadJournalRoles()
    {
        $userJournalRoles = $this->getJournalRoles();
        $this->session->set('userJournalRoles', $userJournalRoles);
    }

    /**
     * 
     * @return array
     */
    public function getJournalRoles()
    {
        $journalService = $this->container->get('ojs.journal_service');
        $user = $this->checkUser();
        if (!$user || !$journalService->getSelectedJournal(false)) {
            return;
        }
        //for API_KEY based connection
        if ($user instanceof \Symfony\Component\Security\Core\User\User) {
            $user = $this->container->get('doctrine')->getManager()->getRepository('OjsUserBundle:User')->findOneBy(['username' => $user->getUsername()]);
        }

        $em = $this->container->get('doctrine')->getManager();
        $repo = $em->getRepository('OjsUserBundle:UserJournalRole');
        $journal = $journalService->getSelectedJournal(false) ;
        $entities = $repo->findBy(array('userId' => $user->getId(), 'journalId' =>$journal->getId() ));
        $userJournalRoles = array();
        if ($entities) {
            foreach ($entities as $entity) {
                $userJournalRoles[] = $entity->getRole();
            }
        }
        return $userJournalRoles;
    }

    /**
     * load users to session that I can login asthem
     * @return void
     */
    public function loadClientUsers()
    {
        $user = $this->checkUser();
        if (!$user) {
            return;
        }

        //for API_KEY based connection
        if ($user instanceof \Symfony\Component\Security\Core\User\User) {
            $user = $this->container->get('doctrine')->getManager()->getRepository('OjsUserBundle:User')->findOneBy(['username' => $user->getUsername()]);
        }

        $clients = $this->container->get('doctrine')->getManager()->getRepository('OjsUserBundle:Proxy')->findBy(
                array('proxyUserId' => $user->getId())
        );
        $this->session->set('userClients', $clients);
    }

    /**
     *
     * @return void
     */
    public function loadJournals()
    {
        $journalService = $this->container->get('ojs.journal_service');
        /** @var User $user */
        $user = $this->checkUser();
        if (!$user) {
            return;
        }

        //for API_KEY based connection
        if ($user instanceof \Symfony\Component\Security\Core\User\User) {
            $user = $this->container->get('doctrine')->getManager()->getRepository('OjsUserBundle:User')->findOneBy(['username' => $user->getUsername()]);
        }

        $em = $this->container->get('doctrine')->getManager();
        $repo = $em->getRepository('OjsUserBundle:UserJournalRole');
        $userJournals = $repo->findByUserId($user->getId());
        if (!is_array($userJournals)) {
            return;
        }
        $journals = array();
        foreach ($userJournals as $item) {
            $journals[$item->getJournalId()] = $item->getJournal();
        }
        if (!$journalService->getSelectedJournal(false)) {
            // set seledctedjournalid session key with first journal in list if no journal selected yet
            $journalService->setSelectedJournal(key($journals));
        }
        $this->session->set('userJournals', $journals);
    }

    /**
     * Check if user is verified. If not redirect to warning page
     */
    public function redirectUnconfirmed()
    {
        $securityContext = $this->container->get('security.context');
        $user = $this->checkUser();
        if ($user && !$securityContext->isGranted('ROLE_USER')) {
            return $this->router->generate('confirm_email_warning');
        }

        return FALSE;
    }

    public function checkUser()
    {
        $securityContext = $this->container->get('security.context');
        $token = $securityContext->getToken();
        if (empty($token)) {
            return FALSE;
        }
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->container->get('security.context')->getToken()->getUser();
        }

        return FALSE;
    }

    /**
     * 
     * @param array $checkRoles
     * @return boolean
     */
    public function hasAnyRole($checkRoles)
    { 
        foreach ($checkRoles as $checkRole) {
            if ($this->container->get('security.context')->isGranted($checkRole->getRole())) {
                return true;
            }
        }
        return false;
    }

    public function hasJournalRole($roleCode)
    {
        $userJournalRoles = $this->session->get('userJournalRoles');
        $user = $this->checkUser();
        if ($user && is_array($userJournalRoles)) {
            foreach ($userJournalRoles as $role) {
                if ($roleCode == $role->getRole()) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * @param User $user
     * @param string $password
     */
    public function changePassword(User &$user, $password,$old_password=false)
    {
        if(empty($password))
            return false;
        $factory = $this->container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);

        if($old_password){
            if (!$encoder->isPasswordValid($user->getPassword(), $old_password, $user->getSalt())) {
                return false;
            }
        }

        $user->setPassword($password);

        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);
        return true;
    }
}
