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
            'ojsuser' => new \Twig_Function_Method($this, 'checkUser', array('is_safe' => array('html')))
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

    /**
     * @todo reformat and validate given issn and output with/without errors
     * @param string $issn
     * @return string
     */
    public function issnValidateFilter($issn, $withErrors = FALSE) {
        return $issn . "---";
    }

    public function getName() {
        return 'ojs_extension';
    }

}
