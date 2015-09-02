<?php

namespace Ojs\Common\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Ojs\Common\Params\ArticleFileParams;
use Ojs\Common\Params\CommonParams;
use Ojs\Common\Services\JournalService;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
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
    /** @var TokenStorageInterface */
    private $tokenStorage;
    /** @var Session */
    private $session;
    /** @var TranslatorInterface */
    private $translator;
    /** @var  string */
    private $cmsShowRoutes;
    /** @var RequestStack */
    private $requestStack;

    /**
     * @param EntityManager $em
     * @param Router $router
     * @param TranslatorInterface $translator
     * @param JournalService $journalService
     * @param TokenStorageInterface $tokenStorage
     * @param Session $session
     * @param RequestStack $requestStack
     * @param null $cmsShowRoutes
     */
    public function __construct(
        EntityManager $em = null,
        Router $router = null,
        TranslatorInterface $translator = null,
        JournalService $journalService = null,
        TokenStorageInterface $tokenStorage = null,
        Session $session = null,
        RequestStack $requestStack,
        $cmsShowRoutes = null
    ) {
        $this->em = $em;
        $this->router = $router;
        $this->journalService = $journalService;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->translator = $translator;
        $this->cmsShowRoutes = $cmsShowRoutes;
        $this->requestStack = $requestStack;
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
            new \Twig_SimpleFunction('userJournalRoles', array($this, 'userJournalRoles')),
            new \Twig_SimpleFunction('isSystemAdmin', array($this, 'isSystemAdmin')),
            new \Twig_SimpleFunction(
                'userjournals', array($this, 'getUserJournals'), array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFunction('userclients', array($this, 'getUserClients'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('session', array($this, 'getSession'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('hasid', array($this, 'hasId'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction(
                'hasIdInObjects', array($this, 'hasIdInObjects'), array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFunction(
                'breadcrumb', array($this, 'generateBreadcrumb'), array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFunction(
                'selectedJournal',
                array($this, 'selectedJournal'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFunction('printYesNo', array($this, 'printYesNo'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('statusText', array($this, 'statusText'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('statusColor', array($this, 'statusColor'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('fileType', array($this, 'fileType'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('daysDiff', array($this, 'daysDiff'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('apiKey', array($this, 'apiKey'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('getObject', array($this, 'getObject'), []),
            new \Twig_SimpleFunction(
                'generateJournalUrl',
                array($this, 'generateJournalUrl'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFunction('getTagDefinition', array($this, 'getTagDefinition')),
            new \Twig_SimpleFunction('getEntity', array($this, 'getEntityObject')),
            new \Twig_SimpleFunction('getAdminPages', array($this, 'getAdminPages')),
            new \Twig_SimpleFunction('isGrantedForPublisher', array($this, 'isGrantedForPublisher')),
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
                '<li class="active">'.$this->translator->trans($item['title']).'</li>' :
                '<li><a  href = "'.$item['link'].'">'.$this->translator->trans($item['title']).'</a></li>';
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
        $token = $this->tokenStorage->getToken();
        if ($token && method_exists($token, 'getUser')) {
            /** @var User $user */
            $user = $token->getUser();

            return $user->isAdmin();
        }

        return false;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
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
     * Check if user is selected journal publisher manager
     *
     * @return bool
     */
    public function isGrantedForPublisher()
    {
        $token = $this->tokenStorage->getToken();
        if ($token && method_exists($token, 'getUser')) {
            $user = $token->getUser();
        } else {
            return false;
        }
        $selectedJournal = $this->journalService->getSelectedJournal();
        if ($selectedJournal) {
            $publisher = $selectedJournal->getPublisher();
        } else {
            $publisherId = $this->requestStack->getCurrentRequest()->attributes->get('publisherId');
            if (!$publisherId) {
                return false;
            }
            $publisher = $this->em->getRepository('OjsJournalBundle:Publisher')->find($publisherId);
        }
        foreach ($publisher->getPublisherManagers() as $manager) {
            if ($manager->getUser()->getId() == $user->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * return translated "yes" or "no" statement after checking $arg
     * @param $arg
     * @return string
     */
    public function printYesNo($arg)
    {
        return ''.
        ($arg ? '<span class="label label-success"><i class="fa fa-check-circle"> '.$this->translator->trans(
                'yes'
            ).'</i></span>' :
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
     * Get current user's api key
     * @return string
     */
    public function apiKey()
    {
        $token = $this->tokenStorage->getToken();
        if ($token && method_exists($token, 'getUser')) {
            /** @var User $user */
            $user = $token->getUser();
            return $user->getApiKey();
        } else {
            return false;
        }
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

        return '<a href="'.$route.'" target="_blank" title="'.$object.'">'.substr($object, 0, 20).'</a>';
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

    /**
     * Returns all AdminPage entities
     *
     * @return array
     */
    public function getAdminPages()
    {
        return $this->em->getRepository('OjsAdminBundle:AdminPage')->findAll();
    }

    public function getName()
    {
        return 'ojs_extension';
    }
}
