<?php

namespace Ojs\JournalBundle\Command;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Gedmo\Mapping\ExtensionODMTest;
use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;
use Ojs\JournalBundle\Entity\Author;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\MailTemplate;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class GuessAuthorUserCommand
 * @package Ojs\JournalBundle\Command
 */
class GuessAuthorUserCommand extends ContainerAwareCommand
{
    const STEP = 50000;

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
            ->setName('ojs:guess:author:user')
            ->setDescription('Guess author user and synchronize')
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
        $totalAuthorCount = $this->getAuthorCount();
        $this->io->progressStart($totalAuthorCount);
        $rsm = new ResultSetMapping();

        for ($count = 0; $count <= $totalAuthorCount; $count+=self::STEP){
            $sql = <<<SQL
        UPDATE author
        SET user_id = users.id
        FROM users
        WHERE author.email = users.email
        AND author.id > ?
        and author.id < ?
        and author.user_id is null
SQL;
            $query = $this->em->createNativeQuery($sql, $rsm);
            $query->setParameter(1, $count);
            $query->setParameter(2, $count+self::STEP);
            $query->getResult();

            if(self::STEP> $totalAuthorCount){
                $this->io->progressFinish();
            }else{
                $this->io->progressAdvance(self::STEP);
            }
        }
        $this->io->newLine(2);
        $this->io->success('All process finished');
    }

    private function getAuthorCount()
    {
        return $this->em->getRepository('OjsJournalBundle:Author')
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
