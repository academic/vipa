<?php

namespace Ojs\CoreBundle\Service;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Row;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class GridAction
 * @package Ojs\CoreBundle\Service
 */
class GridAction
{
    /**
     * @var  CsrfTokenManager
     */
    private $csrfTokenManager;

    /**
     * @var  TranslatorInterface
     */
    private $translator;

    /**
     * @param CsrfTokenManager    $csrfTokenManager
     * @param TranslatorInterface $translator
     */
    public function __construct(
        CsrfTokenManager $csrfTokenManager,
        TranslatorInterface $translator
    ) {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->translator = $translator;
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
        $rowAction->setConfirmMessage($this->translator->trans('sure.ban'));
        if ($role) {
            $rowAction->setRole($role);
        }
        $translator = $this->translator;
        $rowAction->manipulateRender(
            function (RowAction $action, Row $row) use ($translator) {
                if (!$row->getField('enabled')) {
                    $action->setRoute('ojs_admin_user_unblock');
                    $action->setTitle('<i class="fa fa-check"></i>');
                    $action->setConfirmMessage($translator->trans('Are you sure?'));
                }

                return $action;
            }
        );

        return $rowAction;
    }

    /**
     * @param string $route
     * @param string $key
     * @param null $role
     * @param array $options
     * @return RowAction
     */
    public function showAction($route, $key = 'id', $role = null, array $options = array())
    {
        $icon = isset($options['icon']) ? $options['icon']: 'info-circle';
        $title = isset($options['title']) ? $options['title']: 'show';
        $rowAction = new RowAction('<i class="fa fa-'.$icon.'"></i>', $route);
        $rowAction->setAttributes(
            [
                'class' => 'btn btn-success btn-xs  ',
                'data-toggle' => 'tooltip',
                'title' => $this->translator->trans($title),
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
     * @param string $key
     * @param null $role
     * @param array $options
     * @return RowAction
     */
    public function cloneThemeAction($route, $key = 'id', $role = null, array $options = array())
    {
        $icon = isset($options['icon']) ? $options['icon']: 'clone';
        $title = isset($options['title']) ? $options['title']: 'clone';
        $rowAction = new RowAction('<i class="fa fa-'.$icon.'"></i>', $route);
        $rowAction->setAttributes(
            [
                'class' => 'btn btn-success btn-xs  ',
                'data-toggle' => 'tooltip',
                'title' => $this->translator->trans($title),
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
     * @param $route
     * @param $key
     * @param $role
     * @return RowAction
     */
    public function themePreviewAction($route, $key = 'id', $role = '')
    {
        $rowAction = new RowAction('<i class="fa fa-magic"></i>', $route);
        $rowAction->setAttributes(
            [
                'class' => 'btn btn-info btn-xs  ',
                'data-toggle' => 'tooltip',
                'title' => $this->translator->trans("preview"),
            ]
        );
        $rowAction->setTarget('_blank');
        $rowAction->setRouteParameters($key);
        if ($role) {
            $rowAction->setRole($role);
        }

        return $rowAction;
    }

    /**
     * @param string $route
     * @param $key
     * @return RowAction
     */
    public function submissionResumeAction($route, $key = 'id')
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
     * @param string $route
     * @param string $key
     * @param mixed $role
     * @param string $confirmMessage
     * @return RowAction
     */
    public function deleteAction($route, $key = 'id', $role = null, $confirmMessage = 'sure')
    {
        $rowAction = new RowAction('<i class="fa fa-trash-o"></i>', $route);
        $rowAction->setRouteParameters($key);
        $translator = $this->translator;
        $csrfTokenManager = $this->csrfTokenManager;
        $rowAction->manipulateRender(

        /**
         * @param string $action
         */
            function (RowAction $action, Row $row) use ($translator, $csrfTokenManager, $route, $confirmMessage) {
                $route = str_replace('_delete', '', $route);
                $token = $csrfTokenManager->getToken($route.$row->getPrimaryFieldValue());
                $action->setAttributes(
                    [
                        'class' => 'btn btn-danger btn-xs delete',
                        'data-toggle' => 'tooltip',
                        'title' => $translator->trans("delete"),
                        'data-token' => $token,
                        'data-method' => 'delete',
                        'data-confirm' => $this->translator->trans($confirmMessage)
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
}
