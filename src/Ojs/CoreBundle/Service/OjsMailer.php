<?php

namespace Ojs\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserInterface;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\MailTemplate;
use Ojs\UserBundle\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OjsMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $mailSender;

    /**
     * @var string
     */
    private $mailSenderName;

    /**
     * @var EntityManager
     */
    private $em;

    /** @var  TokenStorageInterface */
    private $tokenStorage;

    /** @var  string */
    public $locale;

    /**
     * OjsMailer constructor.
     * @param \Swift_Mailer $mailer
     * @param $mailSender
     * @param $mailSenderName
     * @param RegistryInterface $registry
     * @param TokenStorageInterface $tokenStorage
     * @param $locale
     */
    public function __construct(
        \Swift_Mailer $mailer,
        $mailSender,
        $mailSenderName,
        RegistryInterface $registry,
        TokenStorageInterface $tokenStorage,
        $locale)
    {
        $this->mailer = $mailer;
        $this->mailSender = $mailSender;
        $this->mailSenderName = $mailSenderName;
        $this->em = $registry->getManager();
        $this->tokenStorage = $tokenStorage;
        $this->locale = $locale;
    }

    /**
     * @param UserInterface $user
     * @param string $subject
     * @param string $body
     */
    public function sendToUser(UserInterface $user, $subject, $body)
    {
        if(
            !empty($subject)
            && !empty($body)
            && !empty($user->getEmail())
            && !empty($user->getUsername())
        ){
            $this->send($subject, $body, $user->getEmail(), $user->getUsername());
        }
    }

    /**
     * @param string $subject
     * @param string $body
     * @param string $toMail
     * @param string $toName
     */
    public function send($subject, $body, $toMail, $toName)
    {
        $message = $this->mailer->createMessage();
        $message = $message
            ->setSubject($subject)
            ->addFrom($this->mailSender, $this->mailSenderName)
            ->setTo($toMail, $toName)
            ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection | User[]
     * @link http://stackoverflow.com/a/16692911
     */
    public function getAdminUsers()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
            ->from('OjsUserBundle:User', 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%ROLE_SUPER_ADMIN%');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return mixed
     */
    public function getJournalRelatedUsers()
    {
        return $this->em->getRepository('OjsUserBundle:User')->findUsersByJournalRole(
            ['ROLE_JOURNAL_MANAGER', 'ROLE_EDITOR']
        );
    }

    public function transformTemplate($template, $transformParams = [])
    {
        foreach($transformParams as $transformKey => $transformParam){
            $template = str_replace('[['.$transformKey.']]', $transformParam, $template);
        }
        return $template;
    }

    /**
     * @return mixed
     */
    public function currentUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }

    /**
     * @param $eventName
     * @param null $lang
     * @param Journal|null $journal
     * @return MailTemplate
     */
    public function getEventByName($eventName, $lang = null, Journal $journal = null)
    {
        if($lang == null){
            $lang = $this->locale;
        }
        if($journal == null){
            $GLOBALS['Ojs\JournalBundle\Entity\MailTemplate#journalFilter'] = false;
        }
        /** @var MailTemplate $template */
        $template =  $this->em->getRepository('OjsJournalBundle:MailTemplate')->findOneBy([
            'journal' => $journal,
            'type'    => $eventName,
            'lang'    => $lang,
        ]);
        if($template){
            if($template->isUseJournalDefault()){
                $GLOBALS['Ojs\JournalBundle\Entity\MailTemplate#journalFilter'] = false;
                return $this->em->getRepository('OjsJournalBundle:MailTemplate')->findOneBy([
                    'journal'           => null,
                    'type'              => $eventName,
                    'lang'              => $lang,
                    'journalDefault'    => true,
                ]);
            }
            if(!$template->isActive()){
                return false;
            }
        }
        return $template;
    }
}
