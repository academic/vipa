<?php
/**
 * Created by PhpStorm.
 * User: emreyilmaz
 * Date: 27.02.15
 * Time: 15:47
 */

namespace Ojs\Common\Helper;


use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Row;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class ActionHelper
{
    public static function deleteAction($route, $key)
    {
        $rowAction = new RowAction("", $route);
        $rowAction->setTitle('<i class="fa fa-trash-o"></i>');
        $rowAction->setAttributes(['class' => 'btn btn-danger btn-xs  ']);
        $rowAction->setRouteParameters($key);
        $rowAction->setConfirm(true);
        $rowAction->setConfirmMessage("Do you want delete this row?");
        return $rowAction;
    }

    public static function userBanAction()
    {
        $rowAction = new RowAction("", 'user_block');
        $rowAction->setTitle('<i class="fa fa-ban"></i>');
        $rowAction->setAttributes(['class' => 'btn btn-warning btn-xs  ']);
        $rowAction->setRouteParameters('id');
        $rowAction->setConfirm(true);
        $rowAction->setConfirmMessage("Do you want ban this user?");
        $rowAction->manipulateRender(function (RowAction $action, Row $row) {
            if (!$row->getField('status')) {
                $action->setRoute('user_unblock');
                $action->setTitle('<i class="fa fa-check"></i>');
                $action->setConfirmMessage("Do you want unban this user?");
            }
            return $action;
        });
        return $rowAction;
    }

    public static function switchUserAction($route, $key)
    {
        $rowAction = new RowAction("", $route);
        $rowAction->setTitle('<i class="fa fa-sign-in"></i>');
        $rowAction->setAttributes(['class' => 'btn btn-info btn-xs  ']);
        $rowAction->setRouteParameters($key);
        $rowAction->setRouteParametersMapping(['username' => '_su']);
        $rowAction->setRole('ROLE_SUPER_ADMIN');
        return $rowAction;
    }

    public static function showAction($route, $key)
    {
        $rowAction = new RowAction("", $route);
        $rowAction->setTitle('<i class="fa fa-info-circle"></i>');
        $rowAction->setAttributes(['class' => 'btn btn-success btn-xs  ']);
        $rowAction->setRouteParameters($key);
        return $rowAction;
    }

    public static function editAction($route, $key)
    {
        $rowAction = new RowAction("", $route);
        $rowAction->setTitle('<i class="fa fa-pencil"></i>');
        $rowAction->setAttributes(['class' => 'btn btn-warning btn-xs  ']);
        $rowAction->setRouteParameters($key);
        return $rowAction;
    }

    public static  function userAnonymLoginAction()
    {
        global $kernel;
        $route = 'user_create_anonym_login';

        $rowAction = new RowAction("", $route);
        $rowAction->setTitle('<i class="fa fa-users"></i>');
        $rowAction->setAttributes(['class' => 'btn btn-warning btn-xs  ']);
        $postExtension = $kernel->getContainer()->get('okulbilisimcmsbundle.twig.post_extension');
        $rowAction->setRouteParameters(['id', 'object']);
        $rowAction->setRoute($route);
        $rowAction->manipulateRender(function (RowAction $action, Row $row) use ($postExtension) {
            $entity = $row->getEntity();
            $object = $postExtension->cmsobject($entity);
            $action->setRouteParameters(['id','object'=>$object]);
            return $action;
        });
        return $rowAction;
    }
} 