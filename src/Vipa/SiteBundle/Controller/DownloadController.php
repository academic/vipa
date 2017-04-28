<?php

namespace Vipa\SiteBundle\Controller;

use Vipa\JournalBundle\Entity\ArticleFile;
use Vipa\JournalBundle\Entity\IssueFile;
use Vipa\SiteBundle\Event\DownloadArticleFileEvent;
use Vipa\SiteBundle\Event\DownloadIssueFileEvent;
use Vipa\SiteBundle\Event\SiteEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
Use Symfony\Component\Filesystem\Filesystem;

class DownloadController extends Controller
{
    /**
     * @param ArticleFile $articleFile
     * @return BinaryFileResponse
     */
    public function articleFileAction(ArticleFile $articleFile)
    {
        $fileManager = $this->get('jb_fileuploader.file_history.manager');
        $rootDir = $this->getParameter('kernel.root_dir');
        $assetHelper = $this->get('templating.helper.assets');
        $fileHistory = $fileManager->findOneByFileName($articleFile->getFile());

        $path = $rootDir.'/../web'.$fileManager->getUrl($fileHistory);

        $path = preg_replace('/\?'.$assetHelper->getVersion().'$/', '', $path);

        $explode = explode('.', $fileHistory->getOriginalName());
        $mime = end($explode);

        if (!empty($articleFile->getArticle()->getDoi())){
            $fileOriginalName = $articleFile->getArticle()->getDoi().'-'.$articleFile->getId().'.'.$mime;
        } else {
            $fileOriginalName = $articleFile->getArticle().'-'.$articleFile->getId().'.'.$mime;
        }

        $fileOriginalName = str_replace('/', '-', $fileOriginalName);
        $fileOriginalName = str_replace('\\', '-', $fileOriginalName);

        $fs = new Filesystem();
        if (!$fs->exists($path)) {
            throw $this->createNotFoundException();
        }

        $response = new BinaryFileResponse($path);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            preg_replace('/[[:^print:]]/', '_', $fileOriginalName)
        );

        $event = new DownloadArticleFileEvent($articleFile);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(SiteEvents::DOWNLOAD_ARTICLE_FILE, $event);

        return $response;
    }

    /**
     * @param IssueFile $issueFile
     * @return BinaryFileResponse
     */
    public function issueFileAction(IssueFile $issueFile)
    {
        $fileManager = $this->get('jb_fileuploader.file_history.manager');
        $rootDir = $this->getParameter('kernel.root_dir');
        $assetHelper = $this->get('templating.helper.assets');
        $fileHistory = $fileManager->findOneByFileName($issueFile->getFile());

        $path = $rootDir.'/../web'.$fileManager->getUrl($fileHistory);

        $path = preg_replace('/\?'.$assetHelper->getVersion().'$/', '', $path);
        $fileOriginalName = $fileHistory->getOriginalName();
        if(preg_match('/\//', $fileHistory->getOriginalName())){
            $explode = explode('/', $fileHistory->getOriginalName());
            $fileOriginalName = end($explode);
        }

        $fs = new Filesystem();
        if (!$fs->exists($path)) {
            throw $this->createNotFoundException();
        }
        
        $response = new BinaryFileResponse($path);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            preg_replace('/[[:^print:]]/', '_', $fileOriginalName)
        );

        $event = new DownloadIssueFileEvent($issueFile);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(SiteEvents::DOWNLOAD_ISSUE_FILE, $event);

        return $response;
    }
}
