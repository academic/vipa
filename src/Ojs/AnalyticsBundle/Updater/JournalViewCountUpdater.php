<?php
/**
 * Created by PhpStorm.
 * User: emreyilmaz
 * Date: 28.05.15
 * Time: 12:23
 */

namespace Ojs\AnalyticsBundle\Updater;


use Doctrine\ODM\MongoDB\Cursor;
use Ojs\JournalBundle\Entity\Journal;

class JournalViewCountUpdater extends Updater implements UpdaterInterface {
    public function update()
    {
        // TODO: Implement update() method.
    }

    public function count()
    {
        $ie = $this->em->getRepository('OjsJournalBundle:Journal');
        $all = $ie->findBy(['status'=>3]);
        $journals = [];
        foreach ($all as $r) {
            $yesterday = new \DateTime("@".strtotime("-24 hours"));
            $obv = $this->dm->getRepository('OjsAnalyticsBundle:ObjectViews');
            $views = [];
            /** @var Journal $r */
            //journal views
            /** @var Cursor $journalviews */
            $journalviews = $obv->getAfterFrom($yesterday,'journal',$r->getId());

            $views=array_merge($views,$journalviews->toArray());
            //Article view
            foreach ($r->getArticles() as $article) {
                $articleviews = $obv->getAfterFrom($yesterday,'article',$article->getId());
                $views=array_merge($views,$articleviews->toArray());
            }
            foreach ($r->getIssues() as $issue) {
                $issueViews = $obv->getAfterFrom($yesterday,'issue',$issue->getId());
                $views=array_merge($views,$issueViews->toArray());
            }

            $postRepo = $this->em->getRepository("OkulbilisimCmsBundle:Post");
            $posts = $postRepo->getByObject($this->post_extension->encode($r),$r->getId());

            foreach ($posts as $post) {
                $postViews = $obv->getAfterFrom($yesterday,'post',$post->getId());
                $views = array_merge($views,$postViews->toArray());
            }

            if(count($views)<1)
                continue;
            $journals[$r->getId()] = $views;
        }

        return $journals;
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return 'Ojs\JournalBundle\Entity\Journal';
    }

} 