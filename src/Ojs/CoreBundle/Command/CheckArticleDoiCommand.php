<?php

namespace Ojs\CoreBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Ojs\CoreBundle\Params\DoiStatuses;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CheckArticleDoiCommand extends ContainerAwareCommand
{
    protected $validScopes = ['System', 'Journal', 'Article'];

    /**
     * @var ObjectManager
     */
    protected $em;

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('ojs:doi:check')
            ->setDescription('Check article doi validation')
            ->addOption(
                'check-scope',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Scope for command. Options: '.implode(', ', $this->validScopes)
            )
            ->addOption(
                'id',
                null,
                InputOption::VALUE_OPTIONAL,
                'Journal or Article id'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $io = new SymfonyStyle($input, $output);

        $scope = ucfirst(strtolower($input->getOption('check-scope')));
        if (!in_array($scope, $this->validScopes, true)) {
            $scope = $io->choice('Scope for command', $this->validScopes, $this->validScopes[0]);
        }
        switch ($scope) {
            case 'System':
                $this->systemScope($io);
                break;
            case 'Journal':
                $this->journalScope($io, $input->getOption('id'));
                break;
            case 'Article':
                $this->articleScope($io, $input->getOption('id'));
                break;
        }
    }

    private function systemScope(SymfonyStyle $io)
    {
        /** @var Article[] $articles */
        $articles = $this->getArticleQueryBuilder()
            ->getQuery()
            ->getResult();

        $this->progressArticles($articles, $io);
    }

    private function journalScope(SymfonyStyle $io, $id)
    {
        if (!$id || !is_numeric($id)) {
            $id = $io->ask('Journal\'s id', null, function ($id) {
                if (!is_numeric($id)) {
                    return false;
                }

                return $id;
            });
        }
        if (!$id) {
            $io->caution('Journal not found');

            return;
        }
        /** @var Journal $journal */
        $journal = $this->em->getRepository(Journal::class)->find($id);
        if (!$journal) {
            $io->caution('Journal not found');

            return;
        }
        /** @var Article[] $articles */
        $articles = $this->getArticleQueryBuilder()
            ->andWhere('a.journal = :journal')
            ->setParameter('journal', $journal)
            ->getQuery()
            ->getResult();

        $this->progressArticles($articles, $io);
    }

    private function articleScope(SymfonyStyle $io, $id)
    {
        if (!$id || !is_numeric($id)) {
            $id = $io->ask('Article\'s id', null, function ($id) {
                if (!is_numeric($id)) {
                    return false;
                }

                return $id;
            });
        }
        if (!$id) {
            $io->caution('Article not found');

            return;
        }
        /** @var Article $article */
        $article = $this->em->getRepository(Article::class)->find($id);
        if (!$article) {
            $io->caution('Article not found');

            return;
        }
        if ($article->getDoiStatus() === DoiStatuses::VALID) {
            $io->success('Article\'s doi is valid');

            return;
        }

        if (empty($article->getDoi())) {
            $io->caution('Article has no doi value');

            return;
        }
        try {
            $article = $this->checkArticleDoi($article);
            if ($article->getDoiStatus() === DoiStatuses::VALID) {
                $io->success('Article\'s doi has been validated.');
            }
            if ($article->getDoiStatus() === DoiStatuses::INVALID) {
                $io->caution('Article\'s doi has been invalidated.');
            }
            $this->em->persist($article);
            $this->em->flush();
        } catch (\Exception $e) {
            $io->caution('System error');

            return;
        }
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getArticleQueryBuilder()
    {
        $repository = $this->em->getRepository(Article::class);
        $query = $repository->createQueryBuilder('a')
            ->select('partial a.{id,doi,doiStatus}')
            ->andWhere('a.doi IS NOT NULL')
            ->andWhere('a.doiStatus = '.DoiStatuses::WAITING);

        return $query;
    }

    /**
     * @param Article[]    $articles
     * @param SymfonyStyle $io
     */
    private function progressArticles(array $articles, SymfonyStyle $io)
    {
        $io->progressStart(count($articles));

        $k = 0;
        $validCount = 0;
        $invalidCount = 0;
        foreach ($articles as $article) {
            $article = $this->checkArticleDoi($article);
            if ($article->getDoiStatus() === DoiStatuses::VALID) {
                $validCount++;
            }
            if ($article->getDoiStatus() === DoiStatuses::INVALID) {
                $invalidCount++;
            }
            $this->em->persist($article);
            if ($k === 20) {
                $this->em->flush();
                $k = 0;
            }
            $k++;
            $io->progressAdvance(1);
        }
        $this->em->flush();

        if ($validCount) {
            $io->success(sprintf('%d article doi has been validated.', $validCount));
        }
        if ($invalidCount) {
            $io->caution(sprintf('%d article doi has been invalidated.', $invalidCount));
        }
    }
    /**
     * @param  Article $article
     * @return Article
     */
    private function checkArticleDoi(Article $article)
    {
        try {
            $client = new Client();
            $client->get('http://doi.org/api/handles/'.$article->getDoi());
            $article->setDoiStatus(DoiStatuses::VALID);
        } catch (RequestException $e) {
            $article->setDoiStatus(DoiStatuses::INVALID);
        }

        return $article;
    }
}
