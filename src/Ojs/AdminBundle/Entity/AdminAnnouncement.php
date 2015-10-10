<?php

namespace Ojs\AdminBundle\Entity;

use Ojs\CmsBundle\Entity\Announcement;
use APY\DataGridBundle\Grid\Mapping\Source;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Ojs\CoreBundle\Annotation\Display;

/**
 * AdminAnnouncement
 * @Source(columns="id, title, content")
 */
class AdminAnnouncement extends Announcement
{
    /**
     * @var string
     * @Display\Image(filter="announcement_croped")
     */
    private $image;
}

