<?php

namespace Vipa\JournalBundle\Event;

use APY\DataGridBundle\Grid\Grid;
use Symfony\Component\EventDispatcher\Event;

class ListEvent extends Event
{
    /** @var Grid */
    private $grid;

    /**
     * @return Grid
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @param Grid $grid
     */
    public function setGrid(Grid $grid)
    {
        $this->grid = $grid;
    }
}
