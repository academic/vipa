<?php

namespace Ojs\UserBundle\EventListener;

use Ojs\UserBundle\Event\UserEvent;
use OkulBilisim\WorkflowBundle\Entity\Post;
use OkulBilisim\WorkflowBundle\Event\FlowEvent;
use OkulBilisim\WorkflowBundle\Event\ObjectStepEvent;
use OkulBilisim\WorkflowBundle\Event\PostEvent;
use OkulBilisim\WorkflowBundle\Event\DialogEvent;
use OkulBilisim\WorkflowBundle\WorkFlowEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Ojs\UserBundle\Event\UserEvents;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;

class UserEventListener implements EventSubscriberInterface
{
    /** @var \Swift_Mailer */
    private $mailer;

    /** @var string */
    private $mailSender;

    /** @var string */
    private $mailSenderName;

    /** @var RouterInterface */
    private $router;

    /**
     * @param RouterInterface $router
     * @param \Swift_Mailer   $mailer
     * @param string          $mailSender
     * @param string          $mailSenderName
     *
     */
    public function __construct(
        RouterInterface $router,
        \Swift_Mailer $mailer,
        $mailSender,
        $mailSenderName
    ) {
        $this->router = $router;
        $this->mailer = $mailer;
        $this->mailSender = $mailSender;
        $this->mailSenderName = $mailSenderName;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserEvents::USER_REGISTER => 'onUserRegister',
            UserEvents::USER_INFO_CHANGE => 'onUserInfoChange',
            UserEvents::USER_PASSWORD_CHANGE => 'onUserPasswordChange',
            UserEvents::USER_PASSWORD_RESET => 'onUserPasswordReset',
            UserEvents::USER_LOGIN => 'onUserLogin',
            UserEvents::USER_LOGOUT => 'onUserLogout',
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted'
        );
    }

    public function onUserRegister(UserEvent $userEvent)
    {

    }

    public function onUserInfoChange(UserEvent $userEvent)
    {

    }

    public function onUserPasswordChange(UserEvent $userEvent)
    {

    }

    public function onUserPasswordReset(UserEvent $userEvent)
    {

    }

    public function onUserLogin(UserEvent $userEvent)
    {

    }

    public function onUserLogout(UserEvent $userEvent)
    {

    }

    public function onRegistrationCompleted(FilterUserResponseEvent $userResponseEvent)
    {
        $user = $userResponseEvent->getUser();
        $message = $this->mailer->createMessage();
        $to = array($user->getEmail() => $user->getUsername());
        $message = $message
            ->setSubject(
                'User Event : User Registration'
            )
            ->addFrom($this->mailSender, $this->mailSenderName)
            ->setTo($to)
            ->setBody(
                'User Event -> User Registration Completed -> '. $user->getEmail(),
                'text/html'
            );
        $this->mailer->send($message);
    }

    public function onWorkFlowStarted(FlowEvent $workflowEvent)
    {
        $firstObjectStep = $workflowEvent->getFlow()->getObjectSteps()->first();
        if($firstObjectStep) {
            $this->onPost($firstObjectStep->getDialogs()->first()->getPosts()->first());
        }
    }

    private function onPost(Post $post)
    {
        foreach ($post->getDialog()->getUsers() as $otherUser) {
            if (is_null($otherUser) || is_null($post->getUser()) || $post->getUser()->getId() === $otherUser->getId()) {
                continue;
            }
            $message = $this->mailer->createMessage();
            $to = array($otherUser->getEmail() => $otherUser->getFullName());
            $message = $message
                ->setSubject(
                    'Article Event : '.$post->getDialog()->getObjectStep()->getFlow()->getArticle()->getTitle()
                )
                ->addFrom($this->mailSender, $this->mailSenderName)
                ->setTo($to)
                ->setBody(
                    'A Post created : <a href="'.$this->router->generate(
                        'okul_bilisim_workflow_flow_timeline',
                        array(
                            'journalId' => $post->getDialog()->getObjectStep()->getFlow()->getArticle()->getJournal(
                            )->getId(),
                            'flow' => $post->getDialog()->getObjectStep()->getFlow()->getId(),
                        ),
                        true
                    ).'">LINK</a>',
                    'text/html'
                );
            $this->mailer->send($message);
        }
    }

    public function onObjectStepStarted(ObjectStepEvent $objectStepEvent)
    {
        $this->onPost($objectStepEvent->getObjectStep()->getDialogs()->first()->getPosts()->first());
    }

    public function onDialogStarted(DialogEvent $dialogEvent)
    {
        $this->onPost($dialogEvent->getDialog()->getPosts()->first());
    }

    public function onPostCreated(PostEvent $postEvent)
    {
        $this->onPost($postEvent->getPost());
    }
}