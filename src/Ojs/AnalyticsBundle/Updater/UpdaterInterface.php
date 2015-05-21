<?php

namespace Ojs\AnalyticsBundle\Updater;


interface UpdaterInterface {
    public function update();
    public function count();
}