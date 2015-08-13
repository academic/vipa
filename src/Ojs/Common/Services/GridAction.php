<?php

namespace Ojs\Common\Services;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Row;
use Ojs\CmsBundle\Twig\PostExtension;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class GridAction
 * @package Ojs\Common\Services
 */
class GridAction
{
    /** @var  CsrfTokenManager */
    private $csrfTokenManager;

    /** @var  TranslatorInterface */
    private $translator;

    /** @var  PostExtension */
    private $postExtension;

    /**
     * @param CsrfTokenManager    $csrfTokenManager
     * @param TranslatorInterface $translator
     * @param PostExtension       $postExtension
     */
    public function __construct(
        CsrfTokenManager $csrfTokenManager,
        TranslatorInterface $translator,
        PostExtension $postExtension
    ) {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->translator = $translator;
        $this->postExtension = $postExtension;
    }

    /**
     * @param string $route
     * @param $key
     * @param  null      $role
     * @return RowAction
     */
    public function deleteAction($route, $key = 'id', $role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-trash-o"></i>', $route);
        $rowAction->setRouteParameters($key);
        $translator = $this->translator;
        $csrfTokenManager = $this->csrfTokenManager;
        $rowAction->manipulateRender(

            /**
             * @param string $action
             */
            function (RowAction $action, Row $row) use ($translator, $csrfTokenManager, $route) {
                $route = str_replace('_delete', '', $route);
                $token = $csrfTokenManager->refreshToken($route.$row->getPrimaryFieldValue());
                $action->setAttributes(
                    [
                        'class' => 'btn btn-danger btn-xs delete',
                        'data-toggle' => 'tooltip',
                        'title' => $translator->trans("delete"),
                        'data-token' => $token,
                        'data-method' => 'delete',
                        'data-confirm' => $this->translator->trans("sure")
                    ]
                );

                return $action;
            }
        );
        if ($role) {
            $rowAction->setRole($role);
        }

        return $rowAction;
    }

    /**
     * @param  null      $role
     * @return RowAction
     */
    public function userBanAction($role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-ban"></i>', 'ojs_admin_user_block');
        $rowAction->setAttributes(
            [
                'class' => 'btn btn-warning btn-xs  ',
                'data-toggle' => 'tooltip',
                'title' => $this->translator->trans('block'),
            ]
        );
        $rowAction->setRouteParameters('id');
        $rowAction->setConfirm(true);
        $rowAction->setConfirmMessage($this->translator->trans('Are you sure?'));
        if ($role) {
            $rowAction->setRole($role);
        }
        $translator = $this->translator;
        $rowAction->manipulateRender(
            function (RowAction $action, Row $row) use ($translator) {
                if (!$row->getField('status')) {
                    $action->setRoute('ojs_admin_user_unblock');
                    $action->setTitle('<i class="fa fa-check"></i>');
                    $action->setConfirmMessage($translator->trans('sure.ban'));
                }

                return $action;
            }
        );

        return $rowAction;
    }

    /**
     * @param $route
     * @param $key
     * @param  null      $role
     * @param $mapping_key
     * @return RowAction
     */
    public function switchUserAction($route, $key = 'id', $role = null, $mapping_key = 'username')
    {
        $rowAction = new RowAction('<i class="fa fa-sign-in"></i>', $route);
        $rowAction->setAttributes(
            [
                'class' => 'btn btn-info btn-xs  ',
                'data-toggle' => 'tooltip',
                'title' => $this->translator->trans('login_as'),
            ]
        );
        $rowAction->setRouteParameters($key);
        $rowAction->setRouteParametersMapping([$mapping_key => '_su']);
        if ($role) {
            $rowAction->setRole($role);
        }

        return $rowAction;
    }

    /**
     * @param string $route
     * @param $key
     * @param  null      $role
     * @return RowAction
     */
    public function showAction($route, $key = 'id', $role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-info-circle"></i>', $route);
        $rowAction->setAttributes(
            [
                'class' => 'btn btn-success btn-xs  ',
                'data-toggle' => 'tooltip',
                'title' => $this->translator->trans("show"),
            ]
        );
        $rowAction->setRouteParameters($key);
        if ($role) {
            $rowAction->setRole($role);
        }

        return $rowAction;
    }

    /**
     * @param string $route
     * @param $key
     * @param  null      $role
     * @return RowAction
     */
    public function editAction($route, $key = 'id', $role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-pencil"></i>', $route);
        $rowAction->setAttributes(
            [
                'class' => 'btn btn-warning btn-xs  ',
                'data-toggle' => 'tooltip',
                'title' => $this->translator->trans("edit"),
            ]
        );
        $rowAction->setRouteParameters($key);
        if ($role) {
            $rowAction->setRole($role);
        }

        return $rowAction;
    }

    /**
     * @param $route
     * @param  null      $role
     * @return RowAction
     */
    public function sendMailAction($route, $role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-envelope-o"></i>', $route);
        $rowAction->setAttributes(
            [
                'class' => 'btn-xs btn btn-primary',
                'data-toggle' => 'tooltip',
                'title' => $this->translator->trans("send.mail"),
            ]
        );
        $rowAction->setRouteParameters(['id']);
        $rowAction->setRouteParametersMapping(
            [
                'id' => 'user',
            ]
        );
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
    public function copyAction($route, $key = 'id', $role = '')
    {
        $rowAction = new RowAction('<i class="fa fa-copy"></i>', $route);
        $rowAction->setAttributes(
            [
                'class' => 'btn btn-info btn-xs  ',
                'data-toggle' => 'tooltip',
                'title' => $this->translator->trans("copy"),
            ]
        );
        $rowAction->setRouteParameters($key);
        if ($role) {
            $rowAction->setRole($role);
        }

        return $rowAction;
    }

    /**
     * @param string $route
     * @param $key
     * @param  null      $role
     * @return RowAction
     */
    public function submissionResumeAction($route, $key = 'id', $role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-reply"></i>', $route);
        $rowAction->setAttributes(
            [
                'class' => 'btn btn-warning btn-xs  ',
                'data-toggle' => 'tooltip',
                'title' => $this->translator->trans('ojs.back_and_edit'),
            ]
        );
        $rowAction->setRouteParameters($key);
        $rowAction->setRouteParametersMapping(['id' => 'id']);


        return $rowAction;
    }

    public function submissionCancelAction($route, $key = 'id', $role = null)
    {
        $rowAction = $this->deleteAction($route, $key, $role);
        $rowAction->setRouteParametersMapping(['id' => 'id']);
        return $rowAction;
    }

    /**
     * @param  null      $role
     * @return RowAction
     */
    public function cmsAction($role = null)
    {
        $route = 'ojs_admin_page_index';
        $rowAction = new RowAction('<i class="fa fa-anchor"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-info btn-xs  ', 'data-toggle' => 'tooltip', 'title' => "CMS"]);

        $rowAction->setRouteParameters(['id', 'object']);
        $rowAction->setRoute($route);
        if ($role) {
            $rowAction->setRole($role);
        }
        $postExtension = $this->postExtension;
        $rowAction->manipulateRender(
            function (RowAction $action, Row $row) use ($postExtension) {
                $entity = $row->getEntity();
                $object = $postExtension->cmsobject($entity);
                $action->setRouteParameters(['id', 'object' => $object]);

                return $action;
            }
        );

        return $rowAction;
    }
}
