<?php

namespace Ojs\Common\Services;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Row;
use Okulbilisim\CmsBundle\Twig\PostExtension;
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
     * @param CsrfTokenManager $csrfTokenManager
     * @param TranslatorInterface $translator
     * @param PostExtension $postExtension
     */
    public function __construct(CsrfTokenManager $csrfTokenManager, TranslatorInterface $translator, PostExtension $postExtension) {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->translator = $translator;
        $this->postExtension = $postExtension;
    }

    /**
     * @param $route
     * @param $key
     * @param  null $role
     * @return RowAction
     */
    public function deleteAction($route, $key = 'id', $role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-trash-o"></i>', $route);
        $rowAction->setRouteParameters($key);
        $rowAction->setConfirm(true);
        $rowAction->setConfirmMessage($this->translator->trans("sure"));
        $rowAction->manipulateRender(function (RowAction $action, Row $row) use ($this, $route) {
            $route = str_replace('_delete', '', $route);
            $token = $this->csrfTokenManager->refreshToken($route . $row->getPrimaryFieldValue());
            $action->setAttributes([
                    'class' => 'btn btn-danger btn-xs delete',
                    'data-toggle' => 'tooltip',
                    'title' => $this->translator->trans("delete"),
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
    public function userBanAction($role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-ban"></i>', 'user_block');
        $rowAction->setAttributes(['class' => 'btn btn-warning btn-xs  ', 'data-toggle' => 'tooltip', 'title' => $this->translator->trans('block')]);
        $rowAction->setRouteParameters('id');
        $rowAction->setConfirm(true);
        $rowAction->setConfirmMessage($this->translator->trans('block'));
        if ($role) {
            $rowAction->setRole($role);
        }
        $rowAction->manipulateRender(function (RowAction $action, Row $row) {
            if (!$row->getField('status')) {
                $action->setRoute('user_unblock');
                $action->setTitle('<i class="fa fa-check"></i>');
                $action->setConfirmMessage($this->translator->trans('sure.ban'));
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
    public function switchUserAction($route, $key = 'id', $role = null, $mapping_key = 'username')
    {
        $rowAction = new RowAction('<i class="fa fa-sign-in"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-info btn-xs  ', 'data-toggle' => 'tooltip', 'title' => $this->translator->trans('login_as')]);
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
    public function showAction($route, $key = 'id', $role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-info-circle"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-success btn-xs  ', 'data-toggle' => 'tooltip', 'title' => $this->translator->trans("show")]);
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
    public function editAction($route, $key = 'id', $role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-pencil"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-warning btn-xs  ', 'data-toggle' => 'tooltip', 'title' => $this->translator->trans("edit")]);
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
    public function copyAction($route, $key = 'id', $role = '')
    {
        $rowAction = new RowAction('<i class="fa fa-copy"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-info btn-xs  ', 'data-toggle' => 'tooltip', 'title' => $this->translator->trans("copy")]);
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
    public function submissionResumeAction($route, $key = 'id', $role = null)
    {
        $rowAction = new RowAction('<i class="fa fa-reply"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-warning btn-xs  ', 'data-toggle' => 'tooltip', 'title' => $this->translator->trans('Back & Continue Editing')]);
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
    public function cmsAction($role = null)
    {
        $route = 'okulbilisim_cms_admin';
        $rowAction = new RowAction('<i class="fa fa-anchor"></i>', $route);
        $rowAction->setAttributes(['class' => 'btn btn-info btn-xs  ', 'data-toggle' => 'tooltip', 'title' => "CMS"]);

        $rowAction->setRouteParameters(['id', 'object']);
        $rowAction->setRoute($route);
        if ($role) {
            $rowAction->setRole($role);
        }
        $rowAction->manipulateRender(function (RowAction $action, Row $row) use ($this) {
            $entity = $row->getEntity();
            $object = $this->postExtension->cmsobject($entity);
            $action->setRouteParameters(['id', 'object' => $object]);

            return $action;
        });

        return $rowAction;
    }
}

