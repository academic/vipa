<?php

namespace Vipa\JournalBundle\Event;

use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;


class MenuEvent extends Event
{
    /** @var  ItemInterface */
    private $menuItem;

    /**
     * @return ItemInterface
     */
    public function getMenuItem()
    {
        return $this->menuItem;
    }

    /**
     * @param ItemInterface $menuItem
     * @return MenuEvent
     */
    public function setMenuItem($menuItem)
    {
        $this->menuItem = $menuItem;

        return $this;
    }
}
