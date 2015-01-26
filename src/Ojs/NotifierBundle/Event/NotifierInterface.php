<?php
/**
 * Date: 26.01.15
 * Time: 16:18
 */

namespace Ojs\Common\Event;


interface NotifierInterface {
    public function addQueue(Item $item);
    public function getQueue();
    public function process();
}