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
        if ($journal !== null) {
            $lang = $journal->getMandatoryLang()->getCode();
            $journal->setCurrentLocale($lang); // Use mandatory lang for translation.
            $signature = PHP_EOL.$journal->getMailSignature();
        } else {
            $lang = null;
            $signature = "";
        }

        $template = $this->getTemplateByEvent($event, $lang, $journal);

        if ($template === null) {
            return;
        }

        /** @var User|SubscribeMailList $user */
        foreach ($users as $user) {
            if ($user instanceof SubscribeMailList) {
                $recipientName = $user->getMail();
                $recipientMail = $user->getMail();

                $defaultParams = [
                    'receiver.username' => $user->getMail(),
                    'receiver.fullName' => $user->getMail(),
                    'done.by' => $this->currentUser()->getFullName(),
                ];
            } else {
                $recipientName = $user->getFullName();
                $recipientMail = $user->getEmail();

                $defaultParams = [
                    'receiver.username' => $user->getUsername(),
                    'receiver.fullName' => $user->getFullName(),
                    'done.by' => $this->currentUser()->getFullName(),
                ];
            }

            $templateParams = array_merge($defaultParams, $templateParams);
            $body = $this->transformTemplate($template->getTemplate(), $templateParams);
            $this->send($template->getSubject(), $body.$signature, $recipientMail, $recipientName);
        }
    }

    /**
     * @param UserInterface|User $user
     * @param string $subject
     * @param string $body
     */
    public function sendToUser(User $user, $subject, $body)
    {
        $this->send($subject, $body, $user->getEmail(), $user->getUsername());
    }

    /**
     * @param string $subject
     * @param string $body
     * @param string $toMail
     * @param string $toName
     */
    public function send($subject, $body, $toMail, $toName)
    {
        $mailOk = !empty($subject) && !empty($body);
        $userOk = !empty($toMail) && !empty($toName);

        if (!$mailOk || !$userOk) {
            return;
        }

        $subject = $this->preventMailMerge ? $subject.' rand:'.rand(0, 10000) : $subject;

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
     * @return array
     */
    public function getSubscribers(Journal $journal)
    {
        return $journal->getSubscribeMailLists()->toArray();
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
            return null;
        }

        return $template;
    }
}
