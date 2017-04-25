<?php

namespace Vipa\CoreBundle\Entity;

use JMS\Serializer\Annotation as JMS;

trait AnalyticsTrait
{
    /**
     * @var int
     * @JMS\Expose
     */
    protected $viewCount = 0;
    /**
     * @var int
     * @JMS\Expose
     */
    protected $downloadCount = 0;

    /**
     * @return int
     */
    public function getDownloadCount()
    {
        return $this->downloadCount;
    }

    /**
     * @param  int $downloadCount
     * @return $this
     */
    public function setDownloadCount($downloadCount)
    {
        $this->downloadCount = $downloadCount;

        return $this;
    }

    /**
     * @return $this
     */
    public function increaseDownloadCount()
    {
        $this->downloadCount += 1;

        return $this;
    }

    /**
     * @return int
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * @param  int $viewCount
     * @return $this
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    /**
     * @return $this
     */
    public function increaseViewCount()
    {
        $this->viewCount += 1;

        return $this;
    }
}
