<?php

namespace Ojs\JournalBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Ojs\JournalBundle\Entity\JournalAnnouncement;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Ojs\JournalBundle\Entity\JournalAnnouncementTranslation;

/**
 * Class JournalFooterTextNormalizeCommand
 * @package Ojs\JournalBundle\Command
 */
class JournalFooterTextNormalizeCommand extends ContainerAwareCommand
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
            ->setName('ojs:journal:footertext:normalize')
            ->setDescription('Journal Announcement Normalize')
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
        $this->io->progressStart(count($this->getJournals()));
        $counter = 1;

        foreach($this->getJournals() as $journal){
                $this->addTranslation($journal);
                $this->io->progressAdvance(1);
                $counter = $counter+1;
                if($counter%50 == 0){
                    $this->em->flush();
                }
        }
        $this->em->flush();
        $this->io->success('All process finished');
    }

    private function getJournals()
    {
        $sql = <<<SQL
        SELECT id,footer_text FROM journal WHERE journal.footer_text is not null
SQL;
        
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('footer_text','text');
        $query = $this->em->createNativeQuery($sql, $rsm);
        return $query->getResult();
    }


    private function addTranslation($journal)
    {
        $entity = $this->em->getRepository('OjsJournalBundle:Journal')->find($journal['id']);

        if(!$entity){
            return;
        }

        $entity->setFooterText($journal['text']);

        $this->em->persist($entity);
    }
}
