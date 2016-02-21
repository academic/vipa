<?php

namespace Ojs\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class MailEventsSynchronizeCommand
 * @package Ojs\CoreBundle\Command
 */
class MailEventsSynchronizeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ojs:mail:events:sync')
            ->setDescription('Synchronize all mail events.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $container = $this->getContainer();
        $em = $container->get('doctrine');
        $mailEventClassChain = $container->get('ojs_core.mail.event_chain')->getMailEvents();

        foreach($mailEventClassChain as $mailEventClass){

        }
    }
}
