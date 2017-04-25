<?php

namespace Vipa\CoreBundle\Command;

use Doctrine\ORM\EntityManager;
use Vipa\JournalBundle\Entity\Journal;
use Vipa\JournalBundle\Helper\NumeratorHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NumerateCommand extends ContainerAwareCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('vipa:numerator:numerate')
            ->setDescription('Numerates supported entities')
            ->addArgument('entity', InputArgument::REQUIRED, 'Entity which will be numerated.');
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = $input->getArgument('entity');
        /** @var EntityManager $entityManager */
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        if ($entity === 'article') {
            $output->writeln('<comment>This might take a while.</comment>');
            $journals = $entityManager->getRepository('VipaJournalBundle:Journal')->findAll();
            $totalProgress = new ProgressBar($output, count($journals));
            $totalProgress->setFormat('%current%/%max% [%bar%] %message%');

            if ($totalProgress->getMaxSteps() > 0) {
                $totalProgress->setMessage('Numerating...');
                $totalProgress->start();
            }

            /** @var Journal $journal */
            foreach ($journals as $journal) {
                $articles = $entityManager->getRepository('VipaJournalBundle:Article')->findBy(['journal' => $journal]);
                $totalProgress->setMessage('Numerating articles of "' . $journal->getTitle() . '"');

                foreach ($articles as $article) {
                    NumeratorHelper::numerateArticle($article, $entityManager);
                }

                $totalProgress->advance();
            }

            $totalProgress->finish();
            $output->writeln(''); // Necessary, unfortunately.
            $output->writeln('<info>Done.</info>');
        } else if ($entity === 'issue') {
            $output->writeln('<comment>This might take a while.</comment>');
            $journals = $entityManager->getRepository('VipaJournalBundle:Journal')->findAll();
            $totalProgress = new ProgressBar($output, count($journals));
            $totalProgress->setFormat('%current%/%max% [%bar%] %message%');

            if ($totalProgress->getMaxSteps() > 0) {
                $totalProgress->setMessage('Numerating...');
                $totalProgress->start();
            }

            /** @var Journal $journal */
            foreach ($journals as $journal) {
                $issues = $entityManager->getRepository('VipaJournalBundle:Issue')->findBy(['journal' => $journal]);
                $totalProgress->setMessage('Numerating issues of "' . $journal->getTitle() . '"');

                foreach ($issues as $issue) {
                    NumeratorHelper::numerateIssue($issue, $entityManager);
                }

                $totalProgress->advance();
            }

            $totalProgress->finish();
            $output->writeln(''); // Necessary, unfortunately.
            $output->writeln('<info>Done.</info>');
        } else {
            $output->writeln('<error>This entity is not yet supported.</error>');
        }
    }
}
