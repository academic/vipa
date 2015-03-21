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
        $meta = new \Ojs\Common\Model\Meta(array('title_limit' => 120, 'description_limit' => 200, 'image_limit' => 5));
        if ($article) {
            $meta->meta('DC.Title', $article->getTitle());
            $meta->meta('DC.Description', $article->getAbstract());

            $meta->meta('DC.Source', $article->getJournal()->getTitle());
            $meta->meta('DC.Source.ISSN', $article->getJournal()->getIssn());
            $meta->meta('DC.Source.Issue', $article->getIssue()->getNumber() . "");
            $meta->meta('DC.Source.URI', $this->container->get('ojs.journal_service')->generateUrl($article->getJournal()));
            $meta->meta('DC.Source.Volume', $article->getIssue()->getVolume());
            /*
             * <meta name="DC.Date.created" scheme="ISO8601" content="2014-02-25"/>
              <meta name="DC.Date.dateSubmitted" scheme="ISO8601" content="2014-02-05"/>
              <meta name="DC.Date.issued" scheme="ISO8601" content="2014-05-07"/>
              <meta name="DC.Date.modified" scheme="ISO8601" content="2014-02-25"/>
             */
            $meta->meta('DC.Type', 'Text.Serial.Journal');
            $meta->meta('DC.Type.articleType', '');

            $meta->meta('DC.Contributor.Sponsor', '');
            $meta->meta('DC.Identifier', $article->getId());
            $meta->meta('DC.Identifier.pageNumber', $article->getFirstPage() . '-' . $article->getLastPage());
            $meta->meta('DC.Identifier.DOI', $article->getDoi());
            $meta->meta('DC.Identifier.URI', $this->generateUrl($article));
            $meta->meta('DC.Language', $article->getPrimaryLanguage(), ' scheme="ISO639-1"');
            $meta->meta('DC.Rights', '');


            $articleAuthors = $article->getArticleAuthors();
            $authors = [];
            foreach ($articleAuthors as $articleAuthor) {
                $authors[] = $articleAuthor->getAuthor()->getFullName();
            }
            $meta->meta('DC.Creator.PersonalName', $authors);
            $meta->meta('citation_author', $authors);
            $meta->meta('citation_author_institution', '');
            $meta->meta('citation_title', '');
            $meta->meta('citation_date', '');
            $meta->meta('citation_volume', $article->getIssue()->getVolume());
            $meta->meta('citation_issue', $article->getIssue()->getNumber());
            $meta->meta('citation_firstpage', $article->getFirstPage());
            $meta->meta('citation_lastpage', $article->getLastPage());
            $meta->meta('citation_doi', $article->getDoi());
            $meta->meta('citation_abstract_html_url', $this->generateUrl($article));
            $meta->meta('citation_language', $article->getPrimaryLanguage());
            $meta->meta('citation_keywords', $article->getKeywords());
            $meta->meta('citation_pdf_url', '');
        }
        return $meta;
    }

    /**
     * 
     * @param \Ojs\JournalBundle\Entity\Article $article
     */
    public function generateUrl($article)
    {
        $journalUrl = $this->container->get('ojs.journal_service')->generateUrl($article->getJournal());
        return $journalUrl . '/' . $article->getSlug();
    }

    public function setSelectedJournal($journalId)
    {
        $this->session->set('selectedJournalId', $journalId);
        return $journalId;
    }

}
