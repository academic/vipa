<?php

namespace Ojs\AdminBundle\Entity;

use Ojs\CmsBundle\Entity\Announcement;
use APY\DataGridBundle\Grid\Mapping\Source;

/**
 * AdminAnnouncement
 * @Source(columns="id, title, content")
 */
class AdminAnnouncement extends Announcement
{
}

