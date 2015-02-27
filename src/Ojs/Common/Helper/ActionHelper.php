<?php
/**
 * Created by PhpStorm.
 * User: emreyilmaz
 * Date: 27.02.15
 * Time: 15:47
 */

namespace Ojs\Common\Helper;


use APY\DataGridBundle\Grid\Action\RowAction;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class ActionHelper
{
    public static function deleteAction($route,$key)
    {
        global $kernel;
        $container = $kernel->getContainer();
        /** @var Router $router */
        $html = <<<HTML
             <i class="fa fa-trash-o"></i>
HTML;
        $rowAction = new RowAction("",$route);
        $rowAction->setTitle($html);
        $rowAction->setAttributes(['class'=>'btn btn-danger btn-xs  ']);
        $rowAction->setRouteParameters($key);
        $rowAction->setConfirm(true);
        $rowAction->setConfirmMessage("Do you want delete this row?");
        return $rowAction;
    }
} 