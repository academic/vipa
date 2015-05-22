<?php

namespace Ojs\Common\Twig;

use Doctrine\ORM\EntityManager;
use Ojs\Common\Helper\DateHelper;
use Ojs\Common\Helper\FileHelper;
use Ojs\Common\Params\ArticleFileParams;
use Ojs\Common\Params\CommonParams;
use Ojs\Common\Services\JournalService;
use Ojs\Common\Services\UserListener;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\File;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\Subject;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Entity\UserJournalRole;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class OjsExtension extends \Twig_Extension
{
    /** @var EntityManager  */
    private $em;
    /** @var Router  */
    private $router;
    /** @var JournalService  */
    private $journalService;
    /** @var UserListener  */
    private $userListener;
    /** @var TranslatorInterface  */
    private $translator;
    /** @var  string */
    private $cmsShowRoutes;
    /** @var  string */
    private $avatarUploadBaseUrl;

    public function __construct(
        EntityManager $em = null,
        Router $router = null,
        TranslatorInterface $translator = null,
        JournalService $journalService = null,
        UserListener $userListener = null,
        $cmsShowRoutes = null,
        $avatarUploadBaseUrl = null)
    {
        $this->em = $em;
        $this->router = $router;
        $this->journalService = $journalService;
        $this->userListener = $userListener;
        $this->translator = $translator;
        $this->cmsShowRoutes = $cmsShowRoutes;
        $this->avatarUploadBaseUrl = $avatarUploadBaseUrl;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('issn', array($this, 'issnValidateFilter')),
            new \Twig_SimpleFilter('getDefinition', [$this, 'getDefinition']),
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
            'printYesNo' => new \Twig_Function_Method($this, 'printYesNo', array('is_safe' => array('html'))),
            'statusText' => new \Twig_Function_Method($this, 'statusText', array('is_safe' => array('html'))),
            'statusColor' => new \Twig_Function_Method($this, 'statusColor', array('is_safe' => array('html'))),
            'fileType' => new \Twig_Function_Method($this, 'fileType', array('is_safe' => array('html'))),
            'daysDiff' => new \Twig_Function_Method($this, 'daysDiff', array('is_safe' => array('html'))),
            'apiKey' => new \Twig_Function_Method($this, 'apiKey', array('is_safe' => array('html'))),
            'getRoute' => new \Twig_Function_Method($this, 'getRoute', []),
            'getObject' => new \Twig_Function_Method($this, 'getObject', []),
            'generateJournalUrl' => new \Twig_Function_Method($this, 'generateJournalUrl', array('is_safe' => array('html'))),
            'download' => new \Twig_Function_Method($this, 'downloadArticleFile'),
        );
    }

    public function generateJournalUrl($journal)
    {
        return $this->journalService->generateUrl($journal);
    }

    /**
     * $list =  array( array('link'=>'...','title'=>'...'), array('link'=>'...','title'=>'...') )
     * @param  null   $list
     * @return string
     */
    public function generateBreadcrumb($list = null)
    {
        $html = '<ol class="breadcrumb">';
        for ($i = 0; $i < count($list); ++$i) {
            $item = $list[$i];
            $html .= !isset($item['link']) ?
                '<li class="active">'.$this->translator->trans($item['title']).'</li>' :
                '<li><a  href = "'.$item['link'].'">'.$this->translator->trans($item['title']).'</a></li>';
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
            return false;
        }
        foreach ($haystack as $item) {
            if (isset($item['id']) && $item['id'] == $needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $needle
     * @param $haystack
     * @return bool
     */
    public function hasIdInObjects($needle, $haystack)
    {
        foreach ($haystack as $item) {
            /** @var User $item */
            if ($item->getId() == $needle) {
                return true;
            }
        }

        return false;
    }

    public function getSession($session_key)
    {
        $session = new Session();

        return $session->get($session_key);
    }

    /**
     *
     * @return mixed
     */
    public function getUserJournals()
    {
        $session = new Session();

        return $session->get('userJournals');
    }

    /**
     *
     * @return mixed
     */
    public function getUserClients()
    {
        $session = new Session();

        return $session->get('userClients');
    }

    /**
     * get userJournalRoles session key
     * @return mixed
     */
    public function getUserJournalRoles()
    {
        $session = new Session();

        return $session->get('userJournalRoles');
    }

    /**
     * @return bool
     */
    public function isSystemAdmin()
    {
        $user = $this->userListener->checkUser();
        if ($user) {
            foreach ($user->getRoles() as $role) {
                if ($role->getRole() == 'ROLE_SUPER_ADMIN') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param $roleCode
     * @return bool
     */
    public function hasRole($roleCode)
    {
        $userJournalRoles = $this->getSession('userJournalRoles');
        $user = $this->userListener->checkUser();
        if ($user && is_array($userJournalRoles)) {
            foreach ($userJournalRoles as $role) {
                /** @var UserJournalRole $role */
                if ($roleCode == $role->getRole()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isJournalManager()
    {
        return $this->hasRole('ROLE_JOURNAL_MANAGER');
    }

    /**
     * @return bool
     */
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
        $fileHelper = new FileHelper();

        return $this->avatarUploadBaseUrl.$fileHelper->generatePath($fileName, false).'thumbnail2/'.$fileName;
    }

    public function generateImagePath($file)
    {
        $fileHelper = new FileHelper();

        return $fileHelper->generatePath($file, false).$file;
    }

    public function generateFilePath($file)
    {
        $fileHelper = new FileHelper();

        return $fileHelper->generatePath($file, false).$file;
    }

    /**
     * return translated "yes" or "no" statement after checking $arg
     * @param $arg
     * @return string
     */
    public function printYesNo($arg)
    {
        return ''.
        ($arg ? '<span class="label label-success"><i class="fa fa-check-circle"> '.$this->translator->trans('yes').'</i></span>' :
            '<span class="label label-danger"><i class="fa fa-ban"> '.$this->translator->trans('no').'</i></span>');
    }

    /**
     * Returns status color from given status integer value
     * @param  integer $arg
     * @return string
     */
    public function statusColor($arg)
    {
        $colors = CommonParams::getStatusColors();

        return isset($colors[$arg]) ? $colors[$arg] : '#fff';
    }

    /**
     * Returns status text string from given status integer value
     * @param  integer $arg
     * @return string
     */
    public function statusText($arg)
    {
        $texts = CommonParams::getStatusTexts();

        return isset($texts[$arg]) ? $this->translator->trans($texts[$arg]) : null;
    }

    /**
     * Return file type string from given filetype integer value
     * @param  integer $arg
     * @return string
     */
    public function fileType($arg)
    {
        $text = ArticleFileParams::fileType($arg);

        return $text ? $this->translator->trans($text) : null;
    }

    /**
     *
     * @param  \DateTime $date1
     * @param  \DateTime $date2
     * @return string    formatted string like +12 or -20
     */
    public function daysDiff($date1, $date2)
    {
        $daysFormatted = DateHelper::calculateDaysDiff($date1, $date2);

        return (strpos($daysFormatted, '+') !== false ?
            '<span class="label label-info"  style="background-color: #69f;font-size:10px">' :
            '<span class="label label-danger"  style="background-color: #69f;font-size:10px">')
        .$daysFormatted.' '.$this->translator->trans('days').'</span>';
    }

    /**
     * Get current user's api key
     * @return string
     */
    public function apiKey()
    {
        $user = $this->userListener->checkUser();

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
            'id',
        ];
        foreach ($fields as $field) {
            if (property_exists($object, $field)) {
                return $object->{'get'.strtoupper($field)}();
            }
        }
    }

    public function getRoute($object)
    {
        switch (get_class($object)) {
            case 'Ojs\JournalBundle\Entity\Issue':
                return '#';
            case 'Ojs\JournalBundle\Entity\Journal':
                /** @var Journal $object */
                return $this->router->generate('ojs_journal_index', [
                    'slug' => $object->getSlug(),
                    'institution' => $object->getInstitution()->getSlug(),
                ]);
            case 'Ojs\JournalBundle\Entity\Article':
                /** @var Article $object */
                return $this->router->generate('ojs_article_page', [
                    'slug' => $object->getJournal()->getSlug(),
                    'article_id' => $object->getId(),
                    'institution' => $object->getJournal()->getInstitution()->getSlug(),
                ]);
            case 'Ojs\JournalBundle\Entity\Subject':
                /** @var Subject $object */
                return $this->router->generate('ojs_journals_index', ['subject' => $object->getSlug()]);
            case 'Ojs\JournalBundle\Entity\Institution':
                /** @var Institution $object */
                return $this->router->generate('ojs_institution_page', ['slug' => $object->getSlug()]);
            case 'Ojs\UserBundle\Entity\User':
                /** @var User $object */
                return $this->router->generate('ojs_user_profile', ['slug' => $object->getUsername()]);
            default:
                return '#';
        }
    }

    public function getObject($object, $id)
    {
        $objectClass = $this->decode($object);
        $object = $this->em->find($objectClass, $id);
        $route = $this->router->generate($this->cmsShowRoutes[$objectClass], ['id' => $id]);

        return '<a href="'.$route.'" target="_blank" title="'.$object.'">'.substr($object, 0, 20).'</a>';
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
        $encoded = substr($string, $piece, $len - 1).substr($string, 0, $piece);

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
        $string = substr($string, $piece, $len - 1).substr($string, 0, $piece);
        $decoded = base64_decode($string);

        return $decoded;
    }

    public function downloadArticleFile(File $file)
    {
        return $this->router->generate('ojs_file_download', ['id' => $file->getId()]);
    }

    public function getName()
    {
        return 'ojs_extension';
    }
}
