<?php

namespace Ojs\AnalyticsBundle\Updater;

use Doctrine\MongoDB\Cursor;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleFile;
use Ojs\JournalBundle\Entity\Journal;

class JournalDownloadCountUpdater extends Updater implements UpdaterInterface
{
    public function update()
    {
        // TODO: Implement update() method.
    }

    public function count()
    {
        $ie = $this->em->getRepository('OjsJournalBundle:Journal');
        $all = $ie->findBy(['status' => 3]);
        $journals = [];
        foreach ($all as $r) {
            $yesterday = new \DateTime("@".strtotime("-24 days"));
            $obv = $this->dm->getRepository('OjsAnalyticsBundle:ObjectDownloads');
            $views = [];
            /** @var Journal $r */
            /** @var Cursor $journaldownloads */
            $journaldownloads = $obv->getAfterFrom($yesterday, 'journal', $r->getId());

            $views = array_merge($views, $journaldownloads->toArray());
            //Article download
            foreach ($r->getArticles() as $article) {
                /** @var Article $article */
                $articledownloads = $obv->getAfterFrom($yesterday, 'article', $article->getId());
                $views = array_merge($views, $articledownloads->toArray());

                foreach ($article->getArticleFiles() as $file) {
                    /** @var ArticleFile $file */
                    $fileDownloads = $obv->getAfterFrom($yesterday, 'file', $file->getFile()->getId());
                    $views = array_merge($views, $fileDownloads->toArray());
                }
            }

            if (count($views)<1) {
                continue;
            }
            $journals[$r->getId()] = $views;
        }

        return $journals;
    }

    public function getObject()
    {
        return 'Ojs\JournalBundle\Entity\Journal';
    }
}
