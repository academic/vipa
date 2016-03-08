<?php

namespace Ojs\CoreBundle\Command;

use Ojs\JournalBundle\Entity\Institution;
use Ojs\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserListByRolesCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var ContainerInterface
     */
    private $container;

    protected function configure()
    {
        $this
            ->setName('ojs:user:list:by:role')
            ->setDefinition(
                array(
                    new InputArgument('roles', InputArgument::REQUIRED, 'Comma seperated role list'),
                )
            )
            ->setDescription('Get users by journal role list.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->container    = $this->getContainer();
        $this->em           = $this->container->get('doctrine')->getManager();
        $this->io           = new SymfonyStyle($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title($this->getDescription());
        $roles = explode(',', $input->getArgument('roles'));
        if(!is_array($roles) || count($roles) < 1){
            throw new \LogicException('Specify min. 1 role');
        }
        $users = $this->em->getRepository('OjsUserBundle:User')->findUsersByJournalRole($roles);
        $this->io->writeln('"user_id", "username", "first_name", "last_name", "email"');
        /** @var User $user */
        foreach($users as $user){
            $this->io->writeln(
                sprintf(
                    '%s, "%s", "%s", "%s", "%s"',
                    $user->getId(), $user->getUsername(), $user->getFirstName(), $user->getLastName(), $user->getEmail()
                )
            );
        }
    }
}
