<?php

namespace Vipa\SiteBundle\Controller;

use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\CoreBundle\Params\ArticleStatuses;
use Vipa\CoreBundle\Params\ArticleFileParams;
use Vipa\CoreBundle\Params\JournalStatuses;
use Vipa\CoreBundle\Params\PublisherStatuses;
use OpenJournalSoftware\BibtexBundle\Helper\Bibtex;
use Vipa\JournalBundle\Entity\BlockRepository;
use Vipa\JournalBundle\Entity\Journal;

class ArticleController extends Controller
{
    public function articleWithoutIssuePageAction($slug, $article_id, $isJournalHosting = false)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('VipaJournalBundle:Article')->find($article_id);
        $this->throw404IfNotFound($article);

        $journal = $article->getJournal();

        if($journal->getStatus() !== JournalStatuses::STATUS_PUBLISHED || $journal->getPublisher()->getStatus() !== PublisherStatuses::STATUS_COMPLETE ){
            $journal = null;
            $this->throw404IfNotFound($journal);
        }

        if($article->getStatus() == ArticleStatuses::STATUS_EARLY_PREVIEW){
            $journalService = $this->get('vipa.journal_service');
            $data['article'] = $article;
            //log article view event

            $bibtex = new Bibtex();
            $bibtex->_options['extractAuthors'] = true;
            $bibtex->_options['wordWrapWidth'] = 0;
            $bibtex->authorstring = 'VON LAST, JR, FIRST';

            $createGetterFunction = 'get' . ucfirst('title');

            $fieldTranslations = [];
            foreach ($data['article']->getTranslations() as $langCode => $translation) {
                if (!empty($translation->$createGetterFunction()) && $translation->$createGetterFunction() != '-') {
                    $fieldTranslations[$langCode] = $translation->$createGetterFunction();

                    /*
                     @article{seligman01impact,
                     author = {Len Seligman and Arnon Rosenthal},
                     title = {{XML}'s Impact on Databases and Data Sharing},
                     journal = {Computer},
                     volume = {34},
                     number = {6},
                     year = {2001},
                     issn = {0018-9162},
                     pages = {59--67},
                     doi = {http://dx.doi.org/10.1109/2.928623},
                     publisher = {IEEE Computer Society Press},
                     address = {Los Alamitos, CA, USA},
                     }
                     */
                    $addarray = array();
                    $addarray['entryType'] = $data['article']->getArticleType();
                    $addarray['journal'] = $data['article']->getJournal()->getTitle();
                    $addarray['issn'] = $data['article']->getJournal()->getIssn();
                    $addarray['address'] = $data['article']->getJournal()->getAddress();
                    $addarray['address'] = $data['article']->getJournal()->getPublisher()->getName();
                    if($data['article']->getPubdate()) {
                        $addarray['year'] = $data['article']->getPubdate()->format('Y');
                    }else{
                        $addarray['year'] = '';
                    }
                    $addarray['pages'] = $data['article']->getFirstPage() . ' - ' . $data['article']->getLastPage();
                    $addarray['doi'] = $data['article']->getDoi();
                    $addarray['title'] = $translation->$createGetterFunction();
                    $addarray['language'] = $langCode;
                    $addarray['cite'] = $data['article']->getJournal()->getSlug() . $data['article']->getId();
                    $addarray['key'] = 'cite';
                    foreach ($data['article']->getArticleAuthors() as $author) {
                        $addarray['author'][$author->getAuthorOrder()]['first'] = $author->getAuthor()->getFirstName();
                        $addarray['author'][$author->getAuthorOrder()]['last'] = $author->getAuthor()->getLastName();
                        //$addarray['author'][]['jr'] = $author->getAuthor()->getMiddleName();

                    }
                    arsort($addarray['author']);
                    $bibtex->addEntry($addarray);

                    unset($addarray);
                }
            }
            $data['bibtex'] = ltrim(rtrim(print_r($bibtex->bibTex(), 1)));

            $data['schemaMetaTag'] = '<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />';
            $data['meta'] = $this->get('vipa.article_service')->generateMetaTags($data['article']);
            $data['journal'] = $data['article']->getJournal();
            $data['page'] = 'journals';
            $data['articleFileType'] = ArticleFileParams::$FILE_TYPES;
            $data['blocks'] = $em->getRepository('VipaJournalBundle:Block')->journalBlocks($data['journal']);
            $data['journal']->setPublicURI($journalService->generateUrl($data['journal']));
            $data['archive_uri'] = $this->generateUrl('vipa_archive_index', [
                'slug' => $data['journal']->getSlug(),
                'publisher' => $data['journal']->getPublisher()->getSlug(),
            ], true);
            $data['token'] = $this
                ->get('security.csrf.token_manager')
                ->refreshToken('article_view');


            return $this->render('VipaSiteBundle:Article:article_page.html.twig', $data);
        }

        if($article->getStatus() !== 1 || !$article->getIssue()) {
            $article = null;
            $this->throw404IfNotFound($article);
        }
        $routeParams = array(
            'article_id' => $article->getId(),
            'slug' => $article->getJournal()->getSlug(),
            'issue_id' => $article->getIssue()->getId()
        );
        return $this->redirectToRoute('vipa_article_page', $routeParams);
    }

    /**
     * @param $slug
     * @param $article_id
     * @param null $issue_id
     * @param $isJournalHosting boolean
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articlePageAction($slug, $article_id, $issue_id = null, $isJournalHosting = false)
    {

        $journalService = $this->get('vipa.journal_service');
        $em = $this->getDoctrine()->getManager();
        $data['article'] = $em->getRepository('VipaJournalBundle:Article')->findOneBy(['id' => $article_id, 'status' => ArticleStatuses::STATUS_PUBLISHED]);
        $this->throw404IfNotFound($data['article']);


        $journal = $data['article']->getJournal();

        if($journal->getStatus() !== JournalStatuses::STATUS_PUBLISHED || $journal->getPublisher()->getStatus() !== PublisherStatuses::STATUS_COMPLETE ){
            $journal = null;
            $this->throw404IfNotFound($journal);
        }

        //log article view event

        $bibtex = new Bibtex();
        $bibtex->_options['extractAuthors'] = true;
        $bibtex->_options['wordWrapWidth'] = 0;
        $bibtex->authorstring = 'VON LAST, JR, FIRST';

        $createGetterFunction = 'get' . ucfirst('title');

        $fieldTranslations = [];
        foreach ($data['article']->getTranslations() as $langCode => $translation) {
            if (!empty($translation->$createGetterFunction()) && $translation->$createGetterFunction() != '-') {
                $fieldTranslations[$langCode] = $translation->$createGetterFunction();

                /*
                 @article{seligman01impact,
                 author = {Len Seligman and Arnon Rosenthal},
                 title = {{XML}'s Impact on Databases and Data Sharing},
                 journal = {Computer},
                 volume = {34},
                 number = {6},
                 year = {2001},
                 issn = {0018-9162},
                 pages = {59--67},
                 doi = {http://dx.doi.org/10.1109/2.928623},
                 publisher = {IEEE Computer Society Press},
                 address = {Los Alamitos, CA, USA},
                 }
                 */
                $addarray = array();
                $addarray['entryType'] = $data['article']->getArticleType();
                $addarray['journal'] = $data['article']->getJournal()->getTitle();
                $addarray['issn'] = $data['article']->getJournal()->getIssn();
                $addarray['address'] = $data['article']->getJournal()->getAddress();
                $addarray['address'] = $data['article']->getJournal()->getPublisher()->getName();
                if($data['article']->getPubdate()) {
                    $addarray['year'] = $data['article']->getPubdate()->format('Y');
                }else{
                    $addarray['year'] = '';
                }
                $addarray['volume'] = $data['article']->getIssue()->getVolume();
                $addarray['pages'] = $data['article']->getFirstPage() . ' - ' . $data['article']->getLastPage();
                $addarray['doi'] = $data['article']->getDoi();
                $addarray['title'] = $translation->$createGetterFunction();
                $addarray['language'] = $langCode;
                $addarray['cite'] = $data['article']->getJournal()->getSlug() . $data['article']->getId();
                $addarray['key'] = 'cite';
                foreach ($data['article']->getArticleAuthors() as $author) {
                    $addarray['author'][$author->getAuthorOrder()]['first'] = $author->getAuthor()->getFirstName();
                    $addarray['author'][$author->getAuthorOrder()]['last'] = $author->getAuthor()->getLastName();
                    //$addarray['author'][]['jr'] = $author->getAuthor()->getMiddleName();

                }
                arsort($addarray['author']);
                $bibtex_data[] = $addarray;
                $bibtex->addEntry($addarray);

                unset($addarray);
            }
        }
        $data['bibtex_data'] = $bibtex_data[0];
        $data['bibtex'] = ltrim(rtrim(print_r($bibtex->bibTex(), 1)));

        $data['schemaMetaTag'] = '<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />';
        $data['meta'] = $this->get('vipa.article_service')->generateMetaTags($data['article']);
        $data['journal'] = $data['article']->getJournal();
        $data['isJournalHosting'] = $isJournalHosting;
        $data['page'] = 'journals';
        $data['articleFileType'] = ArticleFileParams::$FILE_TYPES;
        $data['blocks'] = $em->getRepository('VipaJournalBundle:Block')->journalBlocks($data['journal']);
        $data['journal']->setPublicURI($journalService->generateUrl($data['journal']));

        if($isJournalHosting){
            $data['archive_uri'] = $this->generateUrl(
                'journal_hosting_archive',
                [

                ],
                true
            );
        }else{
            $data['archive_uri'] = $this->generateUrl(
                'vipa_archive_index',
                [
                    'slug' => $journal->getSlug()
                ],
                true
            );
        }
        $data['token'] = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('article_view');


        return $this->render('VipaSiteBundle:Article:article_page.html.twig', $data);
    }

    public function journalArticlesAction($slug, $isJournalHosting = false)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('VipaJournalBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('VipaJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);

        if($journal->getStatus() !== JournalStatuses::STATUS_PUBLISHED || $journal->getPublisher()->getStatus() !== PublisherStatuses::STATUS_COMPLETE ){
            $journal = null;
            $this->throw404IfNotFound($journal);
        }


        $articles = $journal->getArticles();
        $data = [
            'journal' => $journal,
            'isJournalHosting' => $isJournalHosting,
            'articles' => $articles,
            'page' => 'journal',
            'blocks' => $blockRepo->journalBlocks($journal),
        ];

        return $this->render('VipaSiteBundle::Article/journal_articles.html.twig', $data);
    }
}
