<?php

namespace Vipa\AnalyticsBundle\Traits;

trait DownloadableTrait
{
    /**
     * @var int
     */
    private $download;

    /**
     * @return int
     */
    public function getDownload()
    {
        return $this->download;
    }

    /**
     * @param int $download
     */
    public function setDownload($download)
    {
        $this->download = $download;
    }
}