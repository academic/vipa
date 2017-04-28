<?php

namespace Vipa\SiteBundle\Event;

final class SiteEvents
{
    const VIEW_ISSUE = 'site.view.issue';
    const VIEW_JOURNAL = 'site.download.journal';
    const VIEW_ARTICLE = 'site.download.article';
    const DOWNLOAD_ISSUE_FILE = 'site.download.issue_file';
    const DOWNLOAD_ARTICLE_FILE = 'site.download.article_file';
}