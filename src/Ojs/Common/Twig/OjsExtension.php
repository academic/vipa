<?php

namespace Ojs\Common\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class OjsExtension extends \Twig_Extension
{

    private $container;
    private $em;

    public function __construct(Container $container = null, \Doctrine\ORM\EntityManager $em = null)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('issn', array($this, 'issnValidateFilter')),
        );
    }

    public function getFunctions()
    {
        return array(
            //'ojsuser' => new \Twig_Function_Method($this, 'checkUser', array('is_safe' => array('html'))),
            'hasRole' => new \Twig_Function_Method($this, 'hasRole'),
            'isSystemAdmin' => new \Twig_Function_Method($this, 'isSystemAdmin'),
            'isJournalManager' => new \Twig_Function_Method($this, 'isJournalManager'),
            'userjournals' => new \Twig_Function_Method($this, 'getUserJournals', array('is_safe' => array('html'))),
            'userclients' => new \Twig_Function_Method($this, 'getUserClients', array('is_safe' => array('html'))),
            'userJournalRoles' => new \Twig_Function_Method($this, 'getUserJournalRoles', array('is_safe' => array('html'))),
            'session' => new \Twig_Function_Method($this, 'getSession', array('is_safe' => array('html'))),
            'hasid' => new \Twig_Function_Method($this, 'hasId', array('is_safe' => array('html'))),
            'breadcrumb' => new \Twig_Function_Method($this, 'generateBreadcrumb', array('is_safe' => array('html'))),
            'selectedJournal' => new \Twig_Function_Method($this, 'selectedJournal', array('is_safe' => array('html'))),
            'generateAvatarPath' => new \Twig_Function_Method($this, 'generateAvatarPath', array('is_safe' => array('html'))),
            'imagePath' => new \Twig_Function_Method($this, 'generateImagePath'),
            'currentJournal' => new \Twig_Function_Method($this, 'getCurrentJournal'),
            'journalTheme' => new \Twig_Function_Method($this, 'journalTheme'),
            'printYesNo' => new \Twig_Function_Method($this, 'printYesNo', array('is_safe' => array('html'))),
            'statusText' => new \Twig_Function_Method($this, 'statusText', array('is_safe' => array('html'))),
            'statusColor' => new \Twig_Function_Method($this, 'statusColor', array('is_safe' => array('html'))),
            'fileType' => new \Twig_Function_Method($this, 'fileType', array('is_safe' => array('html'))),
            'daysDiff' => new \Twig_Function_Method($this, 'daysDiff', array('is_safe' => array('html'))),
        );
    }

    public function getCurrentJournal()
    {
        $journalDomain = $this->container->get('journal_domain');
        return $journalDomain->getCurrentJournal();
    }

    public function journalTheme()
    {
        $journal_domain = $this->container->get('journal_domain');
        if ($journal_domain) {
            $journal = $journal_domain->getCurrentJournal();
            if ($journal) {
                $theme = $journal->getTheme();

                if ($theme) {
                    return $journal->getTheme()->getName();
                }
            }
        }
        return null;
    }

    /**
     *
     * @param array $list
     *                    $list =  array( array('link'=>'...','title'=>'...'), array('link'=>'...','title'=>'...') )
     */
    public function generateBreadcrumb($list = null)
    {

        $translator = $this->container->get('translator');
        $html = '<ol class="breadcrumb">';
        for ($i = 0; $i < count($list); ++$i) {
            $item = $list[$i];
            $html .=!isset($item['link']) ?
                    '<li class="active">' . $translator->trans($item['title']) . '</li>' :
                    '<li><a  href = "' . $item['link'] . '">' . $translator->trans($item['title']) . '</a></li>';
        }
        $html .= '</ol> ';

        return $html;
    }

    /**
     * Check osj user and return user data as array
     * @return \Ojs\UserBundle\Entity\User
     */
    public function checkUser()
    {
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->container->get('security.context')->getToken()->getUser();

            return $user;
        }

        return FALSE;
    }

    /**
     *
     * @param  mixed   $needle
     * @param  array   $haystack
     * @return boolean
     */
    public function hasId($needle, $haystack)
    {
        if (!is_array($haystack)) {
            return FALSE;
        }
        foreach ($haystack as $item) {
            if (isset($item['id']) && $item['id'] == $needle) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function getSession($session_key)
    {
        $session = new \Symfony\Component\HttpFoundation\Session\Session();

        return $session->get($session_key);
    }

    /**
     *
     * @return mixed
     */
    public function getUserJournals()
    {
        $session = new \Symfony\Component\HttpFoundation\Session\Session();

        return $session->get('userJournals');
    }

    /**
     *
     * @return mixed
     */
    public function getUserClients()
    {
        $session = new \Symfony\Component\HttpFoundation\Session\Session();

        return $session->get('userClients');
    }

    /**
     * get userJournalRoles session key
     * @return mixed
     */
    public function getUserJournalRoles()
    {
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        return $session->get('userJournalRoles');
    }

    /**
     * @return \Ojs\UserBundle\Entity\User
     */
    public function isSystemAdmin()
    {
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

    public function hasRole($roleCode)
    {
        $userJournalRoles = $this->getSession('userJournalRoles');
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

    public function isJournalManager()
    {
        return $this->hasRole('ROLE_JOURNAL_MANAGER');
    }

    /**
     * @todo reformat and validate given issn
     * @param  string $issn
     * @return string
     */
    public function issnValidateFilter($issn)
    {
        return $issn;
    }

    public function selectedJournal()
    {
        $selectedJournalId = $this->getSession('selectedJournalId');
        return $selectedJournalId ? $this->em->getRepository('OjsJournalBundle:Journal')->find($selectedJournalId) : null;
    }

    public function generateAvatarPath($fileName)
    {
        $fileHelper = new \Ojs\Common\Helper\FileHelper();
        $rootPath = $this->container->getParameter('avatar_upload_base_url');
        return $rootPath . $fileHelper->generatePath($fileName, false) . 'thumbnail2/' . $fileName;
    }

    public function generateImagePath($file)
    {
        $fileHelper = new \Ojs\Common\Helper\FileHelper();

        return $fileHelper->generatePath($file, false) . $file;
    }

    /**
     * return translated "yes" or "no" statement after checking $arg
     * @param bool $arg
     */
    public function printYesNo($arg)
    {
        $translator = $this->container->get('translator');
        return '' .
                ($arg ? '<span class="label label-success"><i class="fa fa-check-circle"> ' . $translator->trans('yes') . '</i></span>' :
                        '<span class="label label-danger"><i class="fa fa-ban"> ' . $translator->trans('no') . '</i></span>');
    }

    /**
     * Returns status color from given status integer value
     * @param integer $arg
     * @return string
     */
    public function statusColor($arg)
    {
        $colors = \Ojs\Common\Params\CommonParams::getStatusColors();
        return isset($colors[$arg]) ? $colors[$arg] : '#fff';
    }

    /**
     * Returns status text string from given status integer value
     * @param integer $arg
     * @return string 
     */
    public function statusText($arg)
    {
        $translator = $this->container->get('translator');
        $texts = \Ojs\Common\Params\CommonParams::getStatusTexts();
        return isset($texts[$arg]) ? $translator->trans($texts[$arg]) : null;
    }

    /**
     * Return file type string from given filetype integer value
     * @param integer $arg
     * @return string
     */
    public function fileType($arg)
    {
        $translator = $this->container->get('translator');
        $text = \Ojs\Common\Params\ArticleFileParams::fileType($arg);
        return $text ? $translator->trans($text) : null;
    }

    /**
     * 
     * @param DateTime $date1
     * @param DateTime $date2
     * @return string formatted string like +12 or -20 
     */
    public function daysDiff($date1, $date2)
    {
        $translator = $this->container->get('translator');
        $daysFormatted = \Ojs\Common\Helper\DateHelper::calculateDaysDiff($date1, $date2);
        return (strpos($daysFormatted, '+') !== FALSE ?
                        '<span class="label label-info">' :
                        '<span class="label label-danger">')
                . $daysFormatted . ' ' . $translator->trans('days') . '</span>';
    }

    public function getName()
    {
        return 'ojs_extension';
    }

}
