<?php

namespace Ojstr\Common\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserListener
{

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
        $kernel = $event->getKernel();
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
        $user = $this->checkUser();
        if (!$user || !$this->session->get('selectedJournalId')) {
            return;
        }
        $em = $this->container->get('doctrine')->getManager();
        $repo = $em->getRepository('OjstrUserBundle:UserJournalRole');
        $entities = $repo->findBy(array('userId' => $user->getId(), 'journalId' => $this->session->get('selectedJournalId')));
        $userJournalRoles = array();
        if ($entities) {
            foreach ($entities as $entity) {
                $userJournalRoles[] = $entity->getRole();
            }
        }
        $this->session->set('userJournalRoles', $userJournalRoles);
    }

    /**
     * load users to session that I can login asthem
     * @return void
     */
    public function loadClientUsers()
    {
        $user = $this->checkUser();
        if (!$user) {
            return FALSE;
        }
        $clients = $this->container->get('doctrine')->getManager()->getRepository('OjstrUserBundle:Proxy')->findBy(
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
        $user = $this->checkUser();
        if (!$user) {
            return FALSE;
        }
        $em = $this->container->get('doctrine')->getManager();
        $repo = $em->getRepository('OjstrUserBundle:UserJournalRole');
        $userJournals = $repo->findByUserId($user->getId());
        if (!is_array($userJournals)) {
            return;
        }
        $journals = array();
        foreach ($userJournals as $item) {
            $journals[$item->getJournalId()] = $item->getJournal();
        }
        if (!$this->session->get('selectedJournalId')) {
            // set seledctedjournalid session key with first journal in list if no journal selected yet
            $this->session->set('selectedJournalId', key($journals));
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

}
