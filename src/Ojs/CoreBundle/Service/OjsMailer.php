<?php

namespace Ojs\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserInterface;
use Ojs\UserBundle\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;

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
     * OjsMailer constructor.
     * @param \Swift_Mailer $mailer
     * @param string $mailSender
     * @param string $mailSenderName
     */
    public function __construct(\Swift_Mailer $mailer, $mailSender, $mailSenderName,RegistryInterface $registry)
    {
        $this->mailer = $mailer;
        $this->mailSender = $mailSender;
        $this->mailSenderName = $mailSenderName;
        $this->em = $registry->getManager();
    }

    /**
     * @param UserInterface $user
     * @param string $subject
     * @param string $body
     */
    public function sendToUser(UserInterface $user, $subject, $body)
    {
        $this->send($subject, $body, $user->getEmail(), $user->getUsername());
    }

    /**
     * @param $subject
     * @param $body
     * @param $toMail
     * @param $toName
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
}
