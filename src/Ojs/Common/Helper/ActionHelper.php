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

/**
 * Class ActionHelper
 * @package Ojs\Common\Helper
 */
class ActionHelper
{
    /**
     * @param $route
     * @param $key
     * @param null $role
     * @return RowAction
     */
    public static function deleteAction($route, $key,$role=null)
    {
        $rowAction = new RowAction('<i class="fa fa-trash-o"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-danger btn-xs ', 'data-toggle' => 'tooltip','title'=>"Delete"]);
        $rowAction->setRouteParameters($key);
        $rowAction->setConfirm(true);
        $rowAction->setConfirmMessage("Do you want delete this row?");
        if($role){
            $rowAction->setRole($role);
        }
        return $rowAction;
    }

    /**
     * @param null $role
     * @return RowAction
     */
    public static function userBanAction($role=null)
    {
        $rowAction = new RowAction('<i class="fa fa-ban"></i>', 'user_block');
        $rowAction->setAttributes(['class' => 'btn btn-warning btn-xs  ', 'data-toggle' => 'tooltip','title'=>"Block User"]);
        $rowAction->setRouteParameters('id');
        $rowAction->setConfirm(true);
        $rowAction->setConfirmMessage("Do you want ban this user?");
        if($role){
            $rowAction->setRole($role);
        }
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

    /**
     * @param $route
     * @param $key
     * @param null $role
     * @return RowAction
     */
    public static function switchUserAction($route, $key,$role=null)
    {
        $rowAction = new RowAction('<i class="fa fa-sign-in"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-info btn-xs  ', 'data-toggle' => 'tooltip','title'=>"Switch User"]);
        $rowAction->setRouteParameters($key);
        $rowAction->setRouteParametersMapping(['username' => '_su']);
        if($role){
            $rowAction->setRole($role);
        }
        return $rowAction;
    }

    /**
     * @param $route
     * @param $key
     * @param null $role
     * @return RowAction
     */
    public static function showAction($route, $key,$role=null)
    {
        $rowAction = new RowAction('<i class="fa fa-info-circle"></i>', $route); 
        $rowAction->setAttributes(['class' => 'btn btn-success btn-xs  ', 'data-toggle' => 'tooltip','title'=>"Show"]);
        $rowAction->setRouteParameters($key);
        if($role){
            $rowAction->setRole($role);
        }
        return $rowAction;
    }

    /**
     * @param $route
     * @param $key
     * @param null $role
     * @return RowAction
     */
    public static function editAction($route, $key,$role=null)
    {
        $rowAction = new RowAction('<i class="fa fa-pencil"></i>', $route); 
        $rowAction->setAttributes(['class' => 'btn btn-warning btn-xs  ', 'data-toggle' => 'tooltip','title'=>"Edit"]);
        $rowAction->setRouteParameters($key);
        if($role){
            $rowAction->setRole($role);
        }
        return $rowAction;
    }

    /**
     * @param $route
     * @param $key
     * @param null $role
     * @return RowAction
     */
    public static function submissionResumeAction($route, $key,$role=null)
    {
        $rowAction = new RowAction('<i class="fa fa-reply"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-warning btn-xs  ', 'data-toggle' => 'tooltip','title'=>"Resume Submission"]);
        $rowAction->setRouteParameters($key);
        $rowAction->setRouteParametersMapping(['id'=>'submissionId']);
        if($role){
            $rowAction->setRole($role);
        }
        return $rowAction;
    }

    /**
     * @param null $role
     * @return RowAction
     */
    public static function userAnonymLoginAction($role=null)
    {
        global $kernel;
        $route = 'user_create_anonym_login';

        $rowAction = new RowAction('<i class="fa fa-users"></i>', $route); 
        $rowAction->setAttributes(['class' => 'btn btn-warning btn-xs  ', 'data-toggle' => 'tooltip','title'=>"Anonym Login"]);
        $postExtension = $kernel->getContainer()->get('okulbilisimcmsbundle.twig.post_extension');
        $rowAction->setRouteParameters(['id', 'object']);
        $rowAction->setRoute($route);
        if($role){
            $rowAction->setRole($role);
        }
        $rowAction->manipulateRender(function (RowAction $action, Row $row) use ($postExtension) {
            $entity = $row->getEntity();
            $object = $postExtension->cmsobject($entity);
            $action->setRouteParameters(['id', 'object' => $object]);
            return $action;
        });
        return $rowAction;
    }

    /**
     * @param null $role
     * @return RowAction
     */
    public static function cmsAction($role=null)
    {
        //        <a class="btn-xs btn-info" href="{{ path(cms_path, {'id': entity.id, 'object': entity|cmsobject }) }}">
        global $kernel;
        $route = 'okulbilisim_cms_admin';
        $rowAction = new RowAction('<i class="fa fa-anchor"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-info btn-xs  ', 'data-toggle' => 'tooltip','title'=>"CMS"]);
        $postExtension = $kernel->getContainer()->get('okulbilisimcmsbundle.twig.post_extension');
        $rowAction->setRouteParameters(['id', 'object']);
        $rowAction->setRoute($route);
        if($role){
            $rowAction->setRole($role);
        }
        $rowAction->manipulateRender(function (RowAction $action, Row $row) use ($postExtension) {
            $entity = $row->getEntity();
            $object = $postExtension->cmsobject($entity);
            $action->setRouteParameters(['id', 'object' => $object]);
            return $action;
        });
        return $rowAction;
    }
} 