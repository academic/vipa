<?php

namespace Ojs\Common\Services;

use \Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Common methods for article
 */
class ArticleService {

    private $em;
    /* @var \Symfony\Component\DependencyInjection\Container  */
    private $container;

    /**
     * 
     * @param ContainerInterface $container
     * @param EntityManager $em
     */
    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    /**
     * @param \Ojs\JournalBundle\Entity\Article  $article
     * @return  \Laravel\Meta\Meta
     * @throws HttpException
     */
    public function generateMetaTags($article)
    {
        $meta = new \Laravel\Meta\Meta(array('title_limit' => 120, 'description_limit' => 200, 'image_limit' => 5));
        if ($article) {
            $meta->meta('DC.Title', $article->getTitle());
            $meta->meta('DC.Description', $article->getAbstract());


            $meta->meta('DC.Source', $article->getJournal()->getTitle());
            $meta->meta('DC.Source.ISSN', $article->getJournal()->getIssn());
            $meta->meta('DC.Source.Issue',  $article->getIssue()->getNumber()."");
            $meta->meta('DC.Source.URI', '//' . $this->container->get('ojs.journal_service')->generateUrl($article->getJournal()));
            $meta->meta('DC.Source.Volume', $article->getIssue()->getVolume());
        }
        return $meta;
    }

    public function setSelectedJournal($journalId)
    {
        $this->session->set('selectedJournalId', $journalId);
        return $journalId;
    }

}
