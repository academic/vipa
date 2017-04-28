<?php

namespace Vipa\JournalBundle\Command;

use Doctrine\ORM\EntityManager;
use Vipa\JournalBundle\Entity\MailTemplate;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class JournalFooterTextNormalizeCommand
 * @package Vipa\JournalBundle\Command
 */
class MailSubmitterUsernameCommand extends ContainerAwareCommand
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

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('vipa:mail:submitter:username:normalize')
            ->setDescription('Mail Template Submitter Username Normalize')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io               = new SymfonyStyle($input, $output);
        $this->container        = $this->getContainer();
        $this->em               = $this->container->get('doctrine')->getManager();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title($this->getDescription());
        $this->io->progressStart(count($this->getMailTemplates()));
        $counter = 1;
        foreach($this->getMailTemplates() as $mailTemplate){
            $this->updateTemplate($mailTemplate);
            $this->io->progressAdvance(1);
            $counter = $counter+1;
            if($counter%50 == 0){
                $this->em->flush();
            }
        }

        $this->em->flush();
        $this->io->success('All process finished');
    }

    /**
     * @return MailTemplate[]
     */
    private function getMailTemplates()
    {
        $repo = $this->em->getRepository(MailTemplate::class);
        $query = $repo->createQueryBuilder('m')
            ->where('m.template LIKE :search')
            ->setParameter('search','%submitter.username%')
            ->getQuery();

        return $query->getResult();
    }


    /**
     * @param MailTemplate $mailTemplate
     */
    private function updateTemplate(MailTemplate $mailTemplate)
    {
        if(!$mailTemplate){
            return;
        }

        $old = $mailTemplate->getTemplate();
        $new = str_replace('submitter.username','submitter.fullName',$old);

        $mailTemplate->setTemplate($new);

        $this->em->persist($mailTemplate);
    }
}
