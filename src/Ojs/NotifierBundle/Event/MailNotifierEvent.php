<?php
/**
 * Date: 26.01.15
 * Time: 15:30
 */

namespace Ojs\NotifierBundle\Event;


use Ojs\Common\Event\NotifierInterface;
use Ojs\Common\Event\Item;
use Symfony\Component\EventDispatcher\Event;

class MailNotifierEvent extends Event implements NotifierInterface
{
    private $items = [];

    public function addQueue(Item $item)
    {
        $this->items[] = $item;
    }

    public function getQueue()
    {
        return $this->items;
    }

    public function process()
    {
        // TODO: Implement process() method.
    }

}