<?php

namespace Ojs\CoreBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Ojs\CoreBundle\Params\DoiStatuses;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

class CheckArticleDoiCommand extends ContainerAwareCommand
{
    protected $validScopes = ['System', 'Journal', 'Article'];

    /**
     * @var ObjectManager
     */
    protected $em;
    /**
     * @var int
     */
    private $connectionCount;

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
            )
            ->addOption(
                'connection',
                null,
                InputOption::VALUE_OPTIONAL,
                'Connection count',
                50
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('doiCheck');
        $this->connectionCount = $input->getOption('connection');
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

        $event = $stopwatch->stop('doiCheck');

        $io->writeln(['',$event->getMemory()/(1024*1024).' MB memory used in '.($event->getDuration()/1000). ' sn']);
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

        $requests = $this->getRequests($articles);
        $client = new Client();
        $em = $this->em;
        $pool = new Pool($client, $requests, [
            'concurrency' => $this->connectionCount,
            'fulfilled' => function ($response, $index) use ($articles, $em, $io) {
                $io->progressAdvance(1);
                $articleIds[] = rand(0, 100000);
                $article = $articles[$index];
                $article->setDoiStatus(DoiStatuses::VALID);
                $em->persist($article);
                if($index%50 === 0) {
                    $em->flush();
                }
            },
            'rejected' => function ($reason, $index) use ($articles, $em, $io) {
                $io->progressAdvance(1);
                $article = $articles[$index];
                $article->setDoiStatus(DoiStatuses::INVALID);
                $em->persist($article);
                if($index%50 === 0) {
                    $em->flush();
                }
            },
        ]);
        $promise = $pool->promise();

        $promise->wait();
        $em->flush();
    }

    /**
     * @param Article[] $articles
     * @return \Generator
     */
    private function getRequests(array $articles)
    {
        $base = 'http://doi.org/api/handles/';

        foreach ($articles as $article) {
            yield new Request('GET', $base.$article->getDoi());
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
