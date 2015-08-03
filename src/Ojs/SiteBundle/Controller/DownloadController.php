<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\JournalBundle\Entity\ArticleFile;
use Ojs\JournalBundle\Entity\IssueFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;

class DownloadController extends Controller
{
    private function getUploadDirectory()
    {
        return 'uploads';
    }

    public function articleFileAction(ArticleFile $articleFile)
    {
        $path = $this->getUploadDirectory() . '/articlefiles/' . $articleFile->getFile();
        return new BinaryFileResponse($path);
    }

    public function issueFileAction(IssueFile $issueFile)
    {
        $path = $this->getUploadDirectory() . '/journalfiles/' . $issueFile->getFile();
        return new BinaryFileResponse($path);
    }
}
