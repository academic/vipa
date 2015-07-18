<?php

namespace Ojs\Common\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Ojs\Common\Helper\DateHelper;
use Ojs\Common\Helper\FileHelper;
use Ojs\Common\Params\ArticleFileParams;
use Ojs\Common\Params\CommonParams;
use Ojs\Common\Services\JournalService;
use Ojs\Common\Services\UserListener;
use Ojs\JournalBundle\Entity\File;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

class OjsExtension extends \Twig_Extension
{
    /** @var EntityManager */
    private $em;
    /** @var Router */
    private $router;
    /** @var JournalService */
    private $journalService;
    /** @var UserListener */
    private $userListener;
    /** @var TokenStorageInterface */
    private $tokenStorage;
    /** @var Session */
    private $session;
    /** @var TranslatorInterface */
    private $translator;
    /** @var  string */
    private $cmsShowRoutes;
    /** @var  string */
    private $avatarUploadBaseUrl;
    /** @var  string */
    private $defaultInstitutionSlug;
    /** @var  string */
    private $ojs_logo;
    /** @var  string */
    private $ojs_tw;
    /** @var  string */
    private $ojs_fb;

    /**
     * @param EntityManager $em
     * @param Router $router
     * @param TranslatorInterface $translator
     * @param JournalService $journalService
     * @param UserListener $userListener
     * @param TokenStorageInterface $tokenStorage
     * @param Session $session
     * @param null $cmsShowRoutes
     * @param null $avatarUploadBaseUrl
     * @param $defaultInstitutionSlug
     * @param $ojs_logo
     * @param $ojs_tw
     * @param $ojs_fb
     */
    public function __construct(
        EntityManager $em = null,
        Router $router = null,
        TranslatorInterface $translator = null,
        JournalService $journalService = null,
        UserListener $userListener = null,
        TokenStorageInterface $tokenStorage = null,
        Session $session = null,
        $cmsShowRoutes = null,
        $avatarUploadBaseUrl = null,
        $defaultInstitutionSlug,
        $ojs_logo,
        $ojs_tw,
        $ojs_fb
    )
    {
        $this->em = $em;
        $this->router = $router;
        $this->journalService = $journalService;
        $this->userListener = $userListener;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->translator = $translator;
        $this->cmsShowRoutes = $cmsShowRoutes;
        $this->avatarUploadBaseUrl = $avatarUploadBaseUrl;
        $this->defaultInstitutionSlug = $defaultInstitutionSlug;
        $this->ojs_logo = $ojs_logo;
        $this->ojs_tw = $ojs_tw;
        $this->ojs_fb = $ojs_fb;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('issn', array($this, 'issnValidateFilter')),
            new \Twig_SimpleFilter('pop', array($this, 'popFilter')),
        );
    }

    public function getFunctions()
    {
        return array(
            'userJournalRoles' => new \Twig_Function_Method($this, 'userJournalRoles'),
            'isSystemAdmin' => new \Twig_Function_Method($this, 'isSystemAdmin'),
            'userjournals' => new \Twig_Function_Method($this, 'getUserJournals', array('is_safe' => array('html'))),
            'userclients' => new \Twig_Function_Method($this, 'getUserClients', array('is_safe' => array('html'))),
            'session' => new \Twig_Function_Method($this, 'getSession', array('is_safe' => array('html'))),
            'hasid' => new \Twig_Function_Method($this, 'hasId', array('is_safe' => array('html'))),
            'hasIdInObjects' => new \Twig_Function_Method($this, 'hasIdInObjects', array('is_safe' => array('html'))),
            'breadcrumb' => new \Twig_Function_Method($this, 'generateBreadcrumb', array('is_safe' => array('html'))),
            'selectedJournal' => new \Twig_Function_Method($this, 'selectedJournal', array('is_safe' => array('html'))),
            'generateAvatarPath' => new \Twig_Function_Method(
                $this,
                'generateAvatarPath',
                array('is_safe' => array('html'))
            ),
            'imagePath' => new \Twig_Function_Method($this, 'generateImagePath'),
            'filePath' => new \Twig_Function_Method($this, 'generateFilePath'),
            'printYesNo' => new \Twig_Function_Method($this, 'printYesNo', array('is_safe' => array('html'))),
            'statusText' => new \Twig_Function_Method($this, 'statusText', array('is_safe' => array('html'))),
            'statusColor' => new \Twig_Function_Method($this, 'statusColor', array('is_safe' => array('html'))),
            'fileType' => new \Twig_Function_Method($this, 'fileType', array('is_safe' => array('html'))),
            'daysDiff' => new \Twig_Function_Method($this, 'daysDiff', array('is_safe' => array('html'))),
            'apiKey' => new \Twig_Function_Method($this, 'apiKey', array('is_safe' => array('html'))),
            'getObject' => new \Twig_Function_Method($this, 'getObject', []),
            'generateJournalUrl' => new \Twig_Function_Method(
                $this,
                'generateJournalUrl',
                array('is_safe' => array('html'))
            ),
            'download' => new \Twig_Function_Method($this, 'downloadArticleFile'),
            'getTagDefinition' => new \Twig_Function_Method($this, 'getTagDefinition'),
            'getEntity' => new \Twig_Function_Method($this, 'getEntityObject'),
        );
    }

    public function generateJournalUrl($journal)
    {
        return $this->journalService->generateUrl($journal);
    }

    /**
     * $list =  array( array('link'=>'...','title'=>'...'), array('link'=>'...','title'=>'...') )
     * @param  null $list
     * @return string
     */
    public function generateBreadcrumb($list = null)
    {
        $html = '<ol class="breadcrumb">';
        $count = count($list);
        for ($i = 0; $i < $count; ++$i) {
            $item = $list[$i];
            $html .= !isset($item['link']) ?
                '<li class="active">' . $this->translator->trans($item['title']) . '</li>' :
                '<li><a  href = "' . $item['link'] . '">' . $this->translator->trans($item['title']) . '</a></li>';
        }
        $html .= '</ol> ';

        return $html;
    }

    /**
     *
     * @param  mixed $needle
     * @param  array $haystack
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

    /**
     * @param $session_key
     * @return mixed
     */
    public function getSession($session_key)
    {
        return $this->session->get($session_key);
    }

    /**
     *
     * @return mixed
     */
    public function getUserJournals()
    {
        $token = $this->tokenStorage->getToken();
        if ($token instanceof AnonymousToken || !is_object($token)) {
            return array();
        }
        $user = $token->getUser();
        /** @var JournalRepository $journalRepo */
        $journalRepo = $this->em->getRepository('OjsJournalBundle:Journal');
        $userJournals = $journalRepo->findAllByUser($user);
        $journals = array();

        if ($userJournals) {
            foreach ($userJournals as $userJournal) {
                $journals[$userJournal->getId()] = $userJournal;
            }
        } else {
            return array();
        }

        return $journals;
    }

    /**
     *
     * @return mixed
     */
    public function getUserClients()
    {
        return $this->session->get('userClients');
    }

    /**
     * @return bool
     */
    public function isSystemAdmin()
    {
        $user = $this->userListener->checkUser();
        if ($user) {
            return $user->isAdmin();
        }

        return false;
    }

    /**
     * @return array
     */
    public function userJournalRoles()
    {
        return $this->journalService->getSelectedJournalRoles();
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

    /**
     * @return bool|Journal
     */
    public function selectedJournal()
    {
        try {
            return $this->journalService->getSelectedJournal();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $fileName
     * @return string
     */
    public function generateAvatarPath($fileName)
    {
        $fileHelper = new FileHelper();

        return $this->avatarUploadBaseUrl . $fileHelper->generatePath($fileName, false) . 'thumbnail2/' . $fileName;
    }

    /**
     * @param $file
     * @return string
     */
    public function generateImagePath($file)
    {
        $fileHelper = new FileHelper();

        return $fileHelper->generatePath($file, false) . $file;
    }

    /**
     * @param $file
     * @return string
     */
    public function generateFilePath($file)
    {
        $fileHelper = new FileHelper();

        return $fileHelper->generatePath($file, false) . $file;
    }

    /**
     * return translated "yes" or "no" statement after checking $arg
     * @param $arg
     * @return string
     */
    public function printYesNo($arg)
    {
        return '' .
        ($arg ? '<span class="label label-success"><i class="fa fa-check-circle"> ' . $this->translator->trans(
                'yes'
            ) . '</i></span>' :
            '<span class="label label-danger"><i class="fa fa-ban"> ' . $this->translator->trans('no') . '</i></span>');
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
        . $daysFormatted . ' ' . $this->translator->trans('days') . '</span>';
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
     * @param $object
     * @param $id
     * @return string
     * @throws ORMException
     */
    public function getObject($object, $id)
    {
        $objectClass = $this->decode($object);
        $object = $this->em->find($objectClass, $id);
        $route = $this->router->generate($this->cmsShowRoutes[$objectClass], ['id' => $id]);

        return '<a href="' . $route . '" target="_blank" title="' . $object . '">' . substr($object, 0, 20) . '</a>';
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
     * @param  File $file
     * @return string
     */
    public function downloadArticleFile(File $file)
    {
        return $this->router->generate('ojs_file_download', ['id' => $file->getId()]);
    }

    /**
     * Removes specified element from a number indexed array
     * @param  array $array
     * @param  mixed $element
     * @return array
     */
    public function popFilter($array, $element)
    {
        $position = array_search($element, $array);

        if ($position !== false) {
            unset($array[$position]);
        }

        return $array;
    }

    /**
     * Returns entity object from entity name
     *
     * @param $entityObjectName
     * @return object
     */
    public function getEntityObject($entityObjectName)
    {
        $entityClassName = $this->em->getClassMetadata($entityObjectName)->name;

        return new $entityClassName();
    }

    public function getName()
    {
        return 'ojs_extension';
    }
}
