<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\JournalBundle\Entity\ArticleFile;
use Ojs\JournalBundle\Entity\IssueFile;
use Ojs\SiteBundle\Event\DownloadArticleFileEvent;
use Ojs\SiteBundle\Event\DownloadIssueFileEvent;
use Ojs\SiteBundle\Event\SiteEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadController extends Controller
{
    public function articleFileAction(ArticleFile $articleFile)
    {
        $fileManager = $this->get('jb_fileuploader.file_history.manager');
        $rootDir = $this->getParameter('kernel.root_dir');
        $assetHelper = $this->get('templating.helper.assets');
        $fileHistory = $fileManager->findOneByFileName($articleFile->getFile());

        $path = $rootDir.'/../web'.$fileManager->getUrl($fileHistory);
        $path = preg_replace('/\?'.$assetHelper->getVersion().'$/', '', $path);
        $fileOriginalName = $fileHistory->getOriginalName();
        if(preg_match('/\//', $fileHistory->getOriginalName())){
            $explode = explode('/', $fileHistory->getOriginalName());
            $fileOriginalName = end($explode);
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
