<?php

namespace Ojs\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserInterface;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\MailTemplate;
use Ojs\UserBundle\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

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

    /**
     * @var  TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var  string
     */
    public $locale;

    /**
     * @var TranslatorInterface
     */
    public $translator;

    /**
     * @var bool
     */
    public $preventMailMerge;

    /**
     * OjsMailer constructor.
     *
     * @param \Swift_Mailer         $mailer
     * @param string                $mailSender
     * @param string                $mailSenderName
     * @param RegistryInterface     $registry
     * @param TokenStorageInterface $tokenStorage
     * @param string                $locale
     * @param TranslatorInterface   $translator
     * @param bool                  $preventMailMerge
     */
    public function __construct(
        \Swift_Mailer $mailer,
        $mailSender,
        $mailSenderName,
        RegistryInterface $registry,
        TokenStorageInterface $tokenStorage,
        $locale,
        TranslatorInterface $translator,
        $preventMailMerge = false
    )
    {
        $this->mailer           = $mailer;
        $this->mailSender       = $mailSender;
        $this->mailSenderName   = $mailSenderName;
        $this->em               = $registry->getManager();
        $this->tokenStorage     = $tokenStorage;
        $this->locale           = $locale;
        $this->translator       = $translator;
        $this->preventMailMerge = $preventMailMerge;
    }

    /**
     * @param UserInterface|User $user
     * @param string $subject
     * @param string $body
     */
    public function sendToUser(User $user, $subject, $body)
    {
        if(
            !empty($subject)
            && !empty($body)
            && !empty($user->getEmail())
            && !empty($user->getUsername())
        ){
            if($this->preventMailMerge){
                $subject = $subject.' rand:'.rand(0,10000);
            }
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
            ['ROLE_JOURNAL_MANAGER', 'ROLE_EDITOR', 'ROLE_CO_EDITOR']
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
     * @return User
     */
    public function currentUser()
    {
        $token = $this->tokenStorage->getToken();
        if(!$token){
            throw new \LogicException('i can not find current user token :/');
        }
        return $token->getUser();
    }

    /**
     * @param $eventName
     * @param null $lang
     * @param Journal|null $journal
     * @return MailTemplate
     */
    public function getTemplateByEvent($eventName, $lang = null, Journal $journal = null)
    {
        $globalKey = 'Ojs\JournalBundle\Entity\MailTemplate#journalFilter';

        if ($lang == null) {
            $lang = $this->locale;
        }

        if ($journal == null) {
            $GLOBALS[$globalKey] = false;
        }

        $criteria = [
            'journal' => $journal,
            'type'    => $eventName,
            'lang'    => $lang,
        ];

        $template = $this->em->getRepository(MailTemplate::class)->findOneBy($criteria);

        if ($template == null) {
            return null;
        } elseif ($template->isUseJournalDefault()) {
            $GLOBALS[$globalKey] = false;
            array_merge($criteria, ['journal' => null, 'journalDefault' => true]);
            return $this->em->getRepository(MailTemplate::class)->findOneBy($criteria);
        } elseif (!$template->isActive()) {
            return false;
        }

        return $template;
    }
}
