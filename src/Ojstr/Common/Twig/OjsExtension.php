<?php

namespace Ojstr\Common\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class OjsExtension extends \Twig_Extension {

    private $container;

    public function __construct(Container $container = null) {
        $this->container = $container;
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('issn', array($this, 'issnValidateFilter')),
        );
    }

    public function getFunctions() {
        return array(
            'ojsuser' => new \Twig_Function_Method($this, 'checkUser', array('is_safe' => array('html'))),
            'isSystemAdmin' => new \Twig_Function_Method($this, 'isSystemAdmin', array('is_safe' => array('html'))),
            'userjournals' => new \Twig_Function_Method($this, 'getUserJournals', array('is_safe' => array('html'))),
            'session' => new \Twig_Function_Method($this, 'getSession', array('is_safe' => array('html')))
        );
    }

    /**
     * Check osj user and return user data as array
     * @return \Ojstr\UserBundle\Entity\User
     */
    public function checkUser() {
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            return $user;
        }
        return FALSE;
    }

    public function getSession($session_key) {
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        return $session->get($session_key);
    }

    /**
     * 
     * @param integer $user_id
     * @return boolean|mixed
     */
    public function getUserJournals($user_id = NULL) {
        if (empty($user_id)) {
            $user = $this->checkUser();
            if (!$user) {
                return FALSE;
            }
            $user_id = $user->getId();
        }
        $em = $this->container->get('doctrine.orm.entity_manager');
        $journalRepo = $em->getRepository('OjstrJournalBundle:Journal');
        $journals = $journalRepo->getJournals($user_id);
        return $journals;
    }

    /**
     * @return \Ojstr\UserBundle\Entity\User
     */
    public function isSystemAdmin() {
        $user = $this->checkUser();
        if ($user) {
            foreach ($user->getRoles() as $role) {
                if ($role->getRole() == 'ROLE_SUPER_ADMIN') {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * @todo reformat and validate given issn and output with/without errors
     * @param string $issn
     * @return string
     */
    public function issnValidateFilter($issn, $withErrors = FALSE) {
        return $issn;
    }

    public function getName() {
        return 'ojs_extension';
    }

}
