<?php

namespace Ojs\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserInterface;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\MailTemplate;
use Ojs\JournalBundle\Entity\SubscribeMailList;
use Ojs\UserBundle\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

class Mailer
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
     * Mailer constructor.
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
     * @param string $event
     * @param array $users
     * @param array $templateParams
     * @param Journal|null $journal
     */
    public function sendEventMail(string $event, array $users, array $templateParams, Journal $journal = null)
    {
        $lang = $journal === null ?: $journal->getMandatoryLang()->getCode();
        $template = $this->getTemplateByEvent($event, $lang, $journal);

        if ($template === null) {
            return;
        }

        /** @var User $user */
        foreach ($users as $user) {
            $templateParams = array_merge([
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
                'done.by' => $this->currentUser()->getFullName(),
            ], $templateParams);

            $body = $this->transformTemplate($template->getTemplate(), $templateParams);
            $this->sendToUser($user, $template->getSubject(), $body);
        }
    }

    /**
     * @param string $event
     * @param array $subscribers
     * @param array $templateParams
     * @param Journal|null $journal
     */
    public function sendEventMailToSubscribers(string $event, array $subscribers, array $templateParams, Journal $journal = null)
    {
        $lang = $journal === null ?: $journal->getMandatoryLang()->getCode();
        $template = $this->getTemplateByEvent($event, $lang, $journal);

        if ($template === null) {
            return;
        }

        /** @var SubscribeMailList $subscriber */
        foreach ($subscribers as $subscriber) {
            $templateParams = array_merge([
                'receiver.username' => $subscriber->getMail(),
                'receiver.fullName' => $subscriber->getMail(),
                'done.by' => $this->currentUser()->getFullName(),
            ], $templateParams);

            $body = $this->transformTemplate($template->getTemplate(), $templateParams);
            $this->send($template->getSubject(), $body, $subscriber->getMail(), $subscriber->getMail());
        }
    }

    /**
     * @param UserInterface|User $user
     * @param string $subject
     * @param string $body
     */
    public function sendToUser(User $user, $subject, $body)
    {
        $mailOk = !empty($subject) && !empty($body);
        $userOk = !empty($user->getEmail()) && !empty($user->getUsername());

        if ($mailOk && $userOk){
            $subject = $this->preventMailMerge ? $subject.' rand:'.rand(0, 10000) : $subject;
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
     * @return \Doctrine\Common\Collections\Collection|User[]
     */
    public function getAdmins()
    {
        return $this->em->getRepository(User::class)->findAdmins();
    }

    /**
     * @return mixed
     */
    public function getJournalStaff()
    {
        $roles = ['ROLE_JOURNAL_MANAGER', 'ROLE_EDITOR', 'ROLE_CO_EDITOR'];
        return $this->em->getRepository(User::class)->findUsersByJournalRole($roles);
    }

    /**
     * @param Journal $journal
     * @return SubscribeMailList[]
     */
    public function getJournalRelatedMails(Journal $journal)
    {
        return $journal->getSubscribeMailLists();
    }

    public function transformTemplate($template, $parameters = [])
    {
        foreach ($parameters as $key => $value) {
            $template = str_replace('[['.$key.']]', $value, $template);
        }

        return $template;
    }

    /**
     * @return User
     */
    public function currentUser()
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            throw new \LogicException("Could not find a token for the current user.");
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

        if ($template === null) {
            if ($journal !== null) {
                return $this->getTemplateByEvent($eventName, $lang);
            }

            if ($lang !== null) {
                return $this->getTemplateByEvent($eventName);
            }

            return null;
        } elseif ($template->isUseJournalDefault()) {
            $GLOBALS[$globalKey] = false;
            $criteria = array_merge($criteria, ['journal' => null, 'journalDefault' => true]);
            return $this->em->getRepository(MailTemplate::class)->findOneBy($criteria);
        } elseif (!$template->isActive()) {
            return false;
        }

        return $template;
    }
}
