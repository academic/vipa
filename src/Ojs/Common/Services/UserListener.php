<?php

namespace Ojs\Common\Services;

use Doctrine\ORM\EntityManager;
use Ojs\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\User\User as SecurityUser;
use Symfony\Component\Yaml\Parser;

class UserListener
{
    /** @var Session */
    protected $session;
    /** @var Router */
    protected $router;
    /** @var Request */
    protected $request;
    /** @var EntityManager */
    protected $em;
    /** @var  string */
    protected $rootDir;
    /** @var JournalService */
    protected $journalService;
    /** @var EncoderFactory */
    protected $encoderFactory;
    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;
    /** @var TokenStorage */
    protected $tokenStorage;

    /**
     * @param Router                        $router
     * @param EntityManager                 $em
     * @param $rootDir
     * @param JournalService                $journalService
     * @param EncoderFactory                $encoderFactory
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorage                  $tokenStorage
     */
    public function __construct(
        Router $router,
        EntityManager $em,
        $rootDir,
        JournalService $journalService,
        EncoderFactory $encoderFactory,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorage $tokenStorage
    ) {
        $this->router = $router;

        $this->em = $em;
        $this->rootDir = $rootDir;
        $this->journalService = $journalService;
        $this->encoderFactory = $encoderFactory;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->request = $event->getRequest();
        $this->session = $event->getRequest()->getSession();

        if ($event->isMasterRequest()) {
            $this->journalService->setSelectedJournal();
            $this->loadClientUsers();
        }
    }

    /**
     *
     * @param  string  $username
     * @return boolean
     */
    public function checkUsernameAvailability($username)
    {
        $usernameLower = trim(strtolower($username));
        if (strlen($usernameLower) < 4) {
            return false;
        }
        $yamlParser = new Parser();
        $reservedUserNames = $yamlParser->parse(
            file_get_contents(
                $this->rootDir.
                '/../src/Ojs/UserBundle/Resources/data/reservedUsernames.yml'
            )
        );
        $user = $this->em->getRepository('OjsUserBundle:User')->findOneBy(array('username' => $usernameLower));

        return (!$user && !in_array($usernameLower, $reservedUserNames));
    }

    /**
     * load users to session that I can login as them
     * @return void
     */
    public function loadClientUsers()
    {
        $user = $this->checkUser();
        if (!$user) {
            return;
        }

        //for API_KEY based connection
        if ($user instanceof SecurityUser) {
            $user = $this->em->getRepository('OjsUserBundle:User')->findOneBy(['username' => $user->getUsername()]);
        }

        $clients = $this->em->getRepository('OjsUserBundle:Proxy')->findBy(
            array('proxyUserId' => $user->getId())
        );
        $this->session->set('userClients', $clients);
    }

    /**
     * @return bool|User
     */
    public function checkUser()
    {
        $token = $this->tokenStorage->getToken();
        if (empty($token)) {
            return false;
        }
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $token->getUser();
        }

        return false;
    }

    /**
     * @param  User $user
     * @param $password
     * @param  bool $old_password
     * @return bool
     */
    public function changePassword(User &$user, $password, $old_password = false)
    {
        if (empty($password)) {
            return false;
        }
        $encoder = $this->encoderFactory->getEncoder($user);

        if ($old_password) {
            if (!$encoder->isPasswordValid($user->getPassword(), $old_password, $user->getSalt())) {
                return false;
            }
        }

        $user->setPassword($password);

        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);

        return true;
    }
}
