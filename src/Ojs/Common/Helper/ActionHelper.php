<?php
/**
 * Date: 27.02.15
 * Time: 15:47
 */
namespace Ojs\Common\Helper;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Row;
use Okulbilisim\CmsBundle\Twig\PostExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Class ActionHelper
 * @package Ojs\Common\Helper
 */
class ActionHelper
{
    /**
     * @var CsrfTokenManager
     */
    private static $csrf;

    private static $translator;

    public static function setup(CsrfTokenManagerInterface $csrfProvider, $translator)
    {
        self::$csrf = $csrfProvider;
        self::$translator = $translator;
    }

    /**
     * @param $route
     * @param $key
     * @param  null $role
     * @return RowAction
     */
    public static function deleteAction($route, $key, $role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-trash-o"></i>', $route);
        $rowAction->setRouteParameters($key);
        $rowAction->setConfirm(true);
        $rowAction->setConfirmMessage(self::$translator->trans("sure"));
        $csrf = self::$csrf;
        $rowAction->manipulateRender(function (RowAction $action, Row $row) use ($csrf, $route) {
            $route = str_replace('_delete', '', $route);
            if ($csrf instanceof CsrfTokenManagerInterface) {
                $token = $csrf->refreshToken($route . $row->getPrimaryFieldValue());
            } else {
                $token = '';
            }
            $action->setAttributes([
                    'class' => 'btn btn-danger btn-xs delete',
                    'data-toggle' => 'tooltip',
                    'title' => self::$translator->trans("delete"),
                    'data-token' => $token
                ]
            );
            return $action;
        });
        if ($role) {
            $rowAction->setRole($role);
        }

        return $rowAction;
    }


    /**
     * @param  null $role
     * @return RowAction
     */
    public static function userBanAction($role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-ban"></i>', 'user_block');
        $rowAction->setAttributes(['class' => 'btn btn-warning btn-xs  ', 'data-toggle' => 'tooltip', 'title' => self::$translator->trans('block')]);
        $rowAction->setRouteParameters('id');
        $rowAction->setConfirm(true);
        $rowAction->setConfirmMessage(self::$translator->trans('block'));
        if ($role) {
            $rowAction->setRole($role);
        }
        $rowAction->manipulateRender(function (RowAction $action, Row $row) {
            if (!$row->getField('status')) {
                $action->setRoute('user_unblock');
                $action->setTitle('<i class="fa fa-check"></i>');
                $action->setConfirmMessage(self::$translator->trans('sure.ban'));
            }

            return $action;
        });

        return $rowAction;
    }

    /**
     * @param $route
     * @param $key
     * @param  null $role
     * @param $mapping_key
     * @return RowAction
     */
    public static function switchUserAction($route, $key, $role = null, $mapping_key = 'username')
    {
        $rowAction = new RowAction('<i class="fa fa-sign-in"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-info btn-xs  ', 'data-toggle' => 'tooltip', 'title' => self::$translator->trans('login_as')]);
        $rowAction->setRouteParameters($key);
        $rowAction->setRouteParametersMapping([$mapping_key => '_su']);
        if ($role) {
            $rowAction->setRole($role);
        }

        return $rowAction;
    }

    /**
     * @param $route
     * @param $key
     * @param  null $role
     * @return RowAction
     */
    public static function showAction($route, $key, $role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-info-circle"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-success btn-xs  ', 'data-toggle' => 'tooltip', 'title' => self::$translator->trans("show")]);
        $rowAction->setRouteParameters($key);
        if ($role) {
            $rowAction->setRole($role);
        }

        return $rowAction;
    }

    /**
     * @param $route
     * @param $key
     * @param  null $role
     * @return RowAction
     */
    public static function editAction($route, $key, $role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-pencil"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-warning btn-xs  ', 'data-toggle' => 'tooltip', 'title' => self::$translator->trans("edit")]);
        $rowAction->setRouteParameters($key);
        if ($role) {
            $rowAction->setRole($role);
        }

        return $rowAction;
    }

    /**
     * @param $route
     * @param $key
     * @param $role
     * @return RowAction
     */
    public static function copyAction($route, $key, $role = '')
    {
        $rowAction = new RowAction('<i class="fa fa-copy"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-info btn-xs  ', 'data-toggle' => 'tooltip', 'title' => self::$translator->trans("copy")]);
        $rowAction->setRouteParameters($key);
        if ($role) {
            $rowAction->setRole($role);
        }

        return $rowAction;
    }

    /**
     * @param $route
     * @param $key
     * @param  null $role
     * @return RowAction
     */
    public static function submissionResumeAction($route, $key, $role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-reply"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-warning btn-xs  ', 'data-toggle' => 'tooltip', 'title' => self::$translator->trans('Back & Continue Editing')]);
        $rowAction->setRouteParameters($key);
        $rowAction->setRouteParametersMapping(['id' => 'submissionId']);
        if ($role) {
            $rowAction->setRole($role);
        }

        return $rowAction;
    }

    /**
     * @param  null $role
     * @return RowAction
     */
    public static function cmsAction($role = null)
    {
        //        <a class="btn-xs btn-info" href="{{ path(cms_path, {'id': entity.id, 'object': entity|cmsobject }) }}">
        global $kernel;
        $route = 'okulbilisim_cms_admin';
        $rowAction = new RowAction('<i class="fa fa-anchor"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-info btn-xs  ', 'data-toggle' => 'tooltip', 'title' => "CMS"]);
        /** @var PostExtension $postExtension */
        $postExtension = $kernel->getContainer()->get('okulbilisimcmsbundle.twig.post_extension');
        $rowAction->setRouteParameters(['id', 'object']);
        $rowAction->setRoute($route);
        if ($role) {
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

