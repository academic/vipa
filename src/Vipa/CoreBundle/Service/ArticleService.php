<?php

namespace Vipa\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use Vipa\CoreBundle\Model\Meta;
use Vipa\JournalBundle\Entity\Article;
use Vipa\JournalBundle\Entity\ArticleFile;
use Vipa\JournalBundle\Entity\ArticleFileRepository;
use Vipa\JournalBundle\Service\JournalService;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Common methods for article
 */
class ArticleService
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var JournalService
     */
    private $journalService;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param EntityManager  $em
     * @param JournalService $journalService
     * @param Session        $session
     */
    public function __construct(EntityManager $em, JournalService $journalService, Session $session)
    {
        $this->em = $em;
        $this->journalService = $journalService;
        $this->session = $session;
    }

    /**
     * @param  Article       $article
     * @throws HttpException
     * @return Meta
     */
    public function generateMetaTags(Article $article)
    {
        $meta = new Meta(array('title_limit' => 120, 'description_limit' => 200, 'image_limit' => 5));
        if ($article) {
            $meta->meta('DC.Title', $article->getTitle());
            $meta->meta('DC.Description', $article->getAbstract());

            $meta->meta('DC.Source', $article->getJournal()->getTitle());
            !is_null($article->getJournal()) && $meta->meta('DC.Source.ISSN', $article->getJournal()->getIssn());
            !is_null($article->getIssue()) && $meta->meta('DC.Source.Issue', $article->getIssue()->getNumber()."");
            $meta->meta('DC.Source.URI', $this->journalService->generateUrl($article->getJournal()));
            !is_null($article->getIssue()) && $meta->meta('DC.Source.Volume', $article->getIssue()->getVolume());

            !is_null($article->getPubdate()) && $meta->rawMeta(
                'DC.Date.created',
                $article->getPubdate()->format('Y-m-d')
            ); // scheme="ISO8601"
            !is_null($article->getPubdate()) && $meta->rawMeta(
                'DC.Date.dateSubmitted',
                $article->getPubdate()->format('Y-m-d')
            ); // scheme="ISO8601"
            !is_null($article->getPubdate()) && $meta->rawMeta(
                'DC.Date.issued',
                $article->getPubdate()->format('Y-m-d')
            ); //scheme="ISO8601"
            !is_null($article->getPubdate()) && $meta->rawMeta(
                'DC.Date.modified',
                $article->getPubdate()->format('Y-m-d')
            ); // scheme="ISO8601"
            !is_null($article->getPubdate()) && $meta->rawMeta(
                'article:modified_time',
                '<meta content="'.$article->getPubdate()->format('Y-m-d').'" property="article:modified_time"/>'
            );
            !is_null($article->getPubdate()) && $meta->rawMeta(
                'article:publish_time',
                '<meta content="'.$article->getPubdate()->format('Y-m-d').'" property="article:publish_time"/>'
            );
            $meta->rawMeta('og:url', '<meta content="'.$this->generateUrl($article).'" property="og:url"/>');
            $meta->rawMeta('og:title', '<meta content="'.$article->getTitle().'" property="og:title"/>');
            $meta->rawMeta('og:type', '<meta content="article" property="og:type"/>');

            $meta->meta('DC.Type', 'Text.Serial.Journal');
            !is_null($article->getSection()) && $meta->meta('DC.Type.articleType', $article->getSection()->getTitle());

            $meta->meta('DC.Contributor.Sponsor', '');
            $meta->meta('DC.Identifier', $article->getId());
            $meta->meta('DC.Identifier.pageNumber', $article->getFirstPage().'-'.$article->getLastPage());
            $meta->meta('DC.Identifier.DOI', $article->getDoi());
            $meta->meta('DC.Identifier.URI', $this->generateUrl($article));
            $meta->meta('DC.Language', $article->getPrimaryLanguage(). ' scheme="ISO639-1"');
            $meta->meta('DC.Rights', '');

            $articleAuthors = $article->getArticleAuthors();
            $authors = [];
            foreach ($articleAuthors as $articleAuthor) {
                $authors[] = $articleAuthor->getAuthor()->getFullName();
            }
            $meta->meta('DC.Creator.PersonalName', $authors);
            $meta->meta('citation_author', $authors);
            $meta->meta('citation_author_publisher', '');
            $meta->meta('citation_title', $article->getTitle());
            !is_null($article->getPubdate()) && $meta->meta('citation_date', $article->getPubdate()->format('Y-m-d'));
            !is_null($article->getIssue()) && $meta->meta('citation_volume', $article->getIssue()->getVolume());
            !is_null($article->getIssue()) && $meta->meta('citation_issue', $article->getIssue()->getNumber());
            $meta->meta('citation_firstpage', $article->getFirstPage());
            $meta->meta('citation_lastpage', $article->getLastPage());
            $meta->meta('citation_doi', $article->getDoi());
            $meta->meta('citation_abstract_html_url', $this->generateUrl($article));
            $meta->meta('citation_language', $article->getPrimaryLanguage());
            $meta->meta('citation_keywords', $article->getKeywords());
            foreach ($this->getFullTextFiles($article) as $file) {
                $meta->meta('citation_pdf_url', $file->getFile());
            }
        }

        return $meta;
    }

    /**
     *
     * @param  Article $article
     * @return string
     */
    public function generateUrl(Article $article)
    {
        $journalUrl = $this->journalService->generateUrl($article->getJournal());

        return $journalUrl.'/'.$article->getSlug();
    }

    /**
     * @param  Article       $article
     * @return ArticleFile[]
     */
    public function getFullTextFiles(Article $article)
    {
        /** @var ArticleFileRepository $repo */
        $repo = $this->em->getRepository('VipaJournalBundle:ArticleFile');
        $files = $repo->getArticleFullTextFiles($article->getId());

        return $files;
    }
}
