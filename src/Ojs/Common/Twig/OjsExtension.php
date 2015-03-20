<?php

namespace Ojs\Common\Twig;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class OjsExtension extends \Twig_Extension {

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
            new \Twig_SimpleFilter('getDefinition', [$this, 'getDefinition'])
        );
    }

    public function getFunctions()
    {
        return array(
            //'ojsuser' => new \Twig_Function_Method($this, 'checkUser', array('is_safe' => array('html'))),
            'hasRole' => new \Twig_Function_Method($this, 'hasRole'),
            'isSystemAdmin' => new \Twig_Function_Method($this, 'isSystemAdmin'),
            'isJournalManager' => new \Twig_Function_Method($this, 'isJournalManager'),
            'isEditor' => new \Twig_Function_Method($this, 'isEditor'),
            'userjournals' => new \Twig_Function_Method($this, 'getUserJournals', array('is_safe' => array('html'))),
            'userclients' => new \Twig_Function_Method($this, 'getUserClients', array('is_safe' => array('html'))),
            'userJournalRoles' => new \Twig_Function_Method($this, 'getUserJournalRoles', array('is_safe' => array('html'))),
            'session' => new \Twig_Function_Method($this, 'getSession', array('is_safe' => array('html'))),
            'hasid' => new \Twig_Function_Method($this, 'hasId', array('is_safe' => array('html'))),
            'hasIdInObjects' => new \Twig_Function_Method($this, 'hasIdInObjects', array('is_safe' => array('html'))),
            'breadcrumb' => new \Twig_Function_Method($this, 'generateBreadcrumb', array('is_safe' => array('html'))),
            'selectedJournal' => new \Twig_Function_Method($this, 'selectedJournal', array('is_safe' => array('html'))),
            'generateAvatarPath' => new \Twig_Function_Method($this, 'generateAvatarPath', array('is_safe' => array('html'))),
            'imagePath' => new \Twig_Function_Method($this, 'generateImagePath'),
            'filePath' => new \Twig_Function_Method($this, 'generateFilePath'),
            'currentJournal' => new \Twig_Function_Method($this, 'getCurrentJournal'),
            'printYesNo' => new \Twig_Function_Method($this, 'printYesNo', array('is_safe' => array('html'))),
            'statusText' => new \Twig_Function_Method($this, 'statusText', array('is_safe' => array('html'))),
            'statusColor' => new \Twig_Function_Method($this, 'statusColor', array('is_safe' => array('html'))),
            'fileType' => new \Twig_Function_Method($this, 'fileType', array('is_safe' => array('html'))),
            'daysDiff' => new \Twig_Function_Method($this, 'daysDiff', array('is_safe' => array('html'))),
            'apiKey' => new \Twig_Function_Method($this, 'apiKey', array('is_safe' => array('html'))),
            'getRoute' => new \Twig_Function_Method($this, 'getRoute', []),
            'getObject' => new \Twig_Function_Method($this, 'getObject', []),
            'generateJournalUrl' => new \Twig_Function_Method($this, 'generateJournalUrl', array('is_safe' => array('html')))
        );
    }

    public function generateJournalUrl($jorunal)
    {
        return $this->container->get('ojs.journal_service')->generateUrl($jorunal);
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

    /**
     * 
     * @param type $needle
     * @param type $haystack
     */
    public function hasIdInObjects($needle, $haystack)
    {
        foreach ($haystack as $item) {
            if ($item->getId() == $needle) {
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
        $user = $this->container->get('user.helper')->checkUser();
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
        $user = $this->container->get('user.helper')->checkUser();
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

    public function isEditor()
    {
        return $this->hasRole('ROLE_EDITOR');
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

    public function generateFilePath($file)
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
     * @param \DateTime $date1
     * @param \DateTime $date2
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

    /**
     * Get current user's api key
     * @return string
     */
    public function apiKey()
    {
        $user = $this->container->get('user.helper')->checkUser();
        return $user ? $user->getApiKey() : null;
    }

    /**
     * Get object definition field by object type.
     * @param $object
     */
    public function getDefinition($object)
    {
        $fields = [
            'username',
            'title',
            'name',
            'subject',
            'id'
        ];
        foreach ($fields as $field) {
            if (property_exists($object, $field))
                return $object->{'get' . strtoupper($field)}();
        }
    }

    public function getRoute($object)
    {
        $routes = [
            'ojs_institution_page' => ['slug'],
            'ojs_journal_index' => ['journal', 'institution'],
            'ojs_article_page' => ['slug', 'article_slug', 'institution'],
        ];
        $router = $this->container->get('router');

        switch (get_class($object)) {
            case 'Ojs\JournalBundle\Entity\Issue':
                return '#';
            case 'Ojs\JournalBundle\Entity\Journal':
                return $router->generate('ojs_journal_index', [
                            'slug' => $object->getSlug(),
                            'institution' => $object->getInstitution()->getSlug()
                ]);
            case 'Ojs\JournalBundle\Entity\Article':
                return $router->generate('ojs_article_page', [
                            'slug' => $object->getJournal()->getSlug(),
                            'article_slug' => $object->getSlug(),
                            'institution' => $object->getJournal()->getInstitution()->getSlug()
                ]);
            case 'Ojs\JournalBundle\Entity\Subject':
                return $router->generate('ojs_journals_index', ['subject' => $object->getSlug()]);
            case 'Ojs\JournalBundle\Entity\Institution':
                return $router->generate('ojs_institution_page', ['slug' => $object->getSlug()]);
            case 'Ojs\UserBundle\Entity\User':
                return $router->generate('ojs_user_profile', ['slug' => $object->getUsername()]);
            default:
                return '#';
        }
    }

    public function getObject($object, $id)
    {
        $objectClass = $this->decode($object);
        $object = $this->em->find($objectClass, $id);
        $cms_routes = $this->container->getParameter('cms_show_routes');
        /** @var Router $router */
        $router = $this->container->get('router');
        $route = $router->generate($cms_routes[$objectClass], ['id' => $id]);
        return '<a href="' . $route . '" target="_blank" title="' . $object . '">' . substr($object, 0, 20) . '</a>';
    }

    /**
     * Basic encoding
     * @param $string
     * @return string
     */
    public function encode($string)
    {
        $string = base64_encode($string);
        $len = strlen($string);
        $piece = $len / 2;
        $encoded = substr($string, $piece, $len - 1) . substr($string, 0, $piece);
        return $encoded;
    }

    /**
     * Basic encoding
     * @param $string
     * @return string
     */
    public function decode($string)
    {
        $len = strlen($string);
        $piece = $len / 2;
        $string = substr($string, $piece, $len - 1) . substr($string, 0, $piece);
        $decoded = base64_decode($string);
        return $decoded;
    }

    public function getName()
    {
        return 'ojs_extension';
    }

}
