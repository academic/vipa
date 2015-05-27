<?php

namespace Ojs\AnalyticsBundle\Updater;

/**
 * Interface UpdaterInterface
 * @package Ojs\AnalyticsBundle\Updater
 */
interface UpdaterInterface
{
    public function update();

    public function count();

    public function getObject($id);
}
