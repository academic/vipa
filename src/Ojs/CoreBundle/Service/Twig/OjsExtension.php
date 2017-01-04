<?php

namespace Ojs\CoreBundle\Service\Twig;

use Doctrine\ORM\EntityManager;
use Ojs\CoreBundle\Events\TwigEvent;
use Ojs\CoreBundle\Params\ArticleFileParams;
use Ojs\CoreBundle\Params\IssueDisplayModes;
use Ojs\JournalBundle\Entity\Author;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Service\JournalService;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OjsExtension extends \Twig_Extension
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var JournalService
     */
    private $journalService;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param EntityManager             $em
     * @param RouterInterface           $router
     * @param TranslatorInterface       $translator
     * @param JournalService            $journalService
     * @param TokenStorageInterface     $tokenStorage
     * @param Session                   $session
     * @param RequestStack              $requestStack
     * @param EventDispatcherInterface  $eventDispatcher
     */
    public function __construct(
        EntityManager $em = null,
        RouterInterface $router = null,
        TranslatorInterface $translator = null,
        JournalService $journalService = null,
        TokenStorageInterface $tokenStorage = null,
        Session $session = null,
        RequestStack $requestStack,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->em = $em;
        $this->router = $router;
        $this->journalService = $journalService;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->translator = $translator;
        $this->requestStack = $requestStack;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('issn', array($this, 'issnValidateFilter')),
            new \Twig_SimpleFilter('pop', array($this, 'popFilter')),
            new \Twig_SimpleFilter('sanitize', array($this, 'sanitize')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('userJournalRoles', array($this, 'userJournalRoles')),
            new \Twig_SimpleFunction('isSystemAdmin', array($this, 'isSystemAdmin')),
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
            new \Twig_SimpleFunction('fileType', array($this, 'fileType'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('daysDiff', array($this, 'daysDiff'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('apiKey', array($this, 'apiKey'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction(
                'generateJournalUrl',
                array($this, 'generateJournalUrl'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFunction('getTagDefinition', array($this, 'getTagDefinition')),
            new \Twig_SimpleFunction('getEntity', array($this, 'getEntityObject')),
            new \Twig_SimpleFunction('getJournal', array($this, 'getJournal')),
            new \Twig_SimpleFunction('getAdminPages', array($this, 'getAdminPages')),
            new \Twig_SimpleFunction('isGrantedForPublisher', array($this, 'isGrantedForPublisher')),
            new \Twig_SimpleFunction('twigEventDispatch', array($this, 'twigEventDispatch')),
            new \Twig_SimpleFunction('issueTextGenerate', array($this, 'issueTextGenerate')),
            new \Twig_SimpleFunction('getAuthorsInfo', array($this, 'getAuthorsInfo'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('getStrToUpper', array($this, 'getStrToUpper'), array('is_safe' => array('html'))),
        );
    }

    public function sanitize($string)
    {
        $string = strip_tags($string, '<a><blockquote><b><u><i>');
        $dom = new \DOMDocument();
        $dom->loadHTML($string, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        foreach ($dom->getElementsByTagName('*') as $node) {
            /** @var \DOMElement $node */
            for ($i = $node->attributes->length - 1; $i >= 0; $i--) {
                /** @var \DOMAttr $attribute */
                $attribute = $node->attributes->item($i);
                if ($node->nodeName == 'a') {
                    if ($attribute->name === 'href') {
                        $url = filter_var($attribute->value, FILTER_SANITIZE_URL);
                        if (!filter_var($url, FILTER_VALIDATE_URL)) {
                            $node->parentNode->removeChild($node);
                        }
                    } elseif ($attribute->name === 'rel') {
                        $relValues = array(
                            'alternate',
                            'author',
                            'bookmark',
                            'help',
                            'license',
                            'next',
                            'nofollow',
                            'noreferer',
                            'prefetch',
                            'prev',
                            'search',
                            'tag',
                        );
                        if (!in_array($attribute->value, $relValues, true)) {
                            $node->setAttributeNode(new \DOMAttr('rel', 'nofollow'));
                        }
                    } elseif ($attribute->name === 'target') {
                        $targetValues = array(
                            '_blank',
                            '_self',
                            '_parent',
                            '_top',
                        );
                        if (!in_array($attribute->value, $targetValues, true)) {
                            $node->setAttributeNode(new \DOMAttr('target', '_blank'));
                        }
                    } else {
                        $node->removeAttributeNode($attribute);
                    }
                } else {
                    $node->removeAttributeNode($attribute);
                }
            }
        }

        return $dom->saveHTML();
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
            /** @var User $user */
            $user = $token->getUser();
        } else {
            return false;
        }
        $selectedJournal = $this->journalService->getSelectedJournal();
        if ($selectedJournal) {
            $publisher = $selectedJournal->getPublisher();
            if($publisher == null){
                return false;
            }
        } else {
            $publisherId = $this->requestStack->getCurrentRequest()->attributes->get('publisherId');
            if (!$publisherId) {
                return false;
            }
            $publisher = $this->em->getRepository('OjsJournalBundle:Publisher')->find($publisherId);
        }
        if ($user->isAdmin()) {
            return true;
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
     * @param integer $journal_id
     * @return Journal
     */
    public function getJournal($journal_id)
    {
        return $this->em->getRepository('OjsJournalBundle:Journal')->find($journal_id);

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

    public function twigEventDispatch($options)
    {
        $twigEvent = new TwigEvent($options);
        $eventName = constant('Ojs\CoreBundle\Events\TwigEvents::'.$options['event_name']);
        $dispatchEvent = $this->eventDispatcher->dispatch($eventName, $twigEvent);
        return $dispatchEvent->getTemplate();
    }

    /**
     * @param Issue $issue
     * @return string
     */
    public function issueTextGenerate(Issue $issue)
    {
        $issueText = '';
        if($issue->getDisplayMode() == null || $issue->getDisplayMode() == IssueDisplayModes::SHOW_ALL){

            if(!empty($issue->getVolume())){
                $issueText.= $this->translator->trans('volume').': '.$issue->getVolume().' ';
            }
            if(!empty($issue->getNumber())){
                $issueText.= $this->translator->trans('issue').': '.$issue->getNumber();
            }
            if(!empty($issue->getTitle()) && $issue->getTitle() !== '-'){
                $issueText.= ' - '.$issue->getTitle();
            }
            return $issueText;
        }elseif($issue->getDisplayMode() == IssueDisplayModes::SHOW_VOLUME_AND_NUMBER){

            if(!empty($issue->getVolume())){
                $issueText.= $this->translator->trans('volume').': '.$issue->getVolume().' ';
            }
            if(!empty($issue->getNumber())){
                $issueText.= $this->translator->trans('issue').': '.$issue->getNumber();
            }
            return $issueText;
        }elseif($issue->getDisplayMode() == IssueDisplayModes::SHOW_TITLE){
            return $issue->getTitle();
        }
        return $issueText;
    }

    /**
     * @param Author $author
     * @return string
     */
    public function getAuthorsInfo(Author $author)
    {

        $institution = (!empty($author->getInstitution())) ? $author->getInstitution() : $author->getInstitutionName();
        $email = (empty($author->getUser())) ? $author->getEmail() : $author->getUser()->getEmail();
        $fullName = $author->getFullName();

        $text = '
        <p id="author$' . $author->getId() . '">
        <b>' . $this->translator->trans('author') . ': </b>' . $fullName . '</br>';

        if (!empty($email)){
            $text .= '<b>' . $this->translator->trans('email') . ': </b>' . $email . '</br>';
        }
        if (!empty($institution)){
            $text .= '<b>' . $this->translator->trans('institution') . ': </b>' . $institution . '</br>';
        }
        if (!empty($author->getCountry())){
            $text .= '<b>' . $this->translator->trans('country') . ': </b>' . $author->getCountry() . '</p><hr>';
        }


        return $text;


    }

    /**
     * @param $string
     * @return string
     */
    public function getStrToUpper($string)
    {
        $string = str_replace(array('i', 'ı', 'ü', 'ğ', 'ş', 'ö', 'ç'), array('İ', 'I', 'Ü', 'Ğ', 'Ş', 'Ö', 'Ç'), $string);
        
        return strtoupper($string);
    }

    public function getName()
    {
        return 'ojs_extension';
    }
}
