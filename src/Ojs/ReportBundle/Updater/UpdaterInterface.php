<?php

namespace Ojs\ReportBundle\Updater;

/**
 * Interface UpdaterInterface
 * @package Ojs\ReportBundle\Updater
 */
interface UpdaterInterface
{
    public function update();

    public function count();

    public function getObject();
}
