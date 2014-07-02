<?php

namespace Ojstr\Common\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserListener {

    protected $container;
    protected $session;

    public function __construct(ContainerInterface $container) { // this is @service_container
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $kernel = $event->getKernel();
        $request = $event->getRequest();
        $this->session = $request->getSession();

        if ($event->isMasterRequest()) {
            $this->loadJournals();
            $this->loadJournalRoles();
        }
        // fill journal roles
    }

    /**
     * get user's roles for selected journal and save to userJournalRoles session key
     * @return void
     */
    public function loadJournalRoles() {
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
     * 
     * @return void
     */
    public function loadJournals() {
        $user = $this->checkUser();
        if (!$user) {
            return;
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

    public function checkUser() {
        $securityContext = $this->container->get('security.context');
        $token = $securityContext->getToken();
        if (empty($token))) {
            return FALSE;
        }
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->container->get('security.context')->getToken()->getUser();
        }
        return FALSE;
    }

}
