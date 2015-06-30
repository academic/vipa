<?php

namespace Ojs\Common\Twig;

use Symfony\Component\Translation\TranslatorInterface;

class ActionExtension extends \Twig_Extension {

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Returns the name of the extension.
     * @return string The extension name
     */
    public function getName()
    {
        return 'action_extension';
    }

    /**
     * Returns a list of functions to add to the existing list.
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'actions' => new \Twig_Function_Method($this, 'actions', array('is_safe' => array('html'))),
        );
    }

    /**
     * Generates action buttons with Bootstrap theming
     * @param array $actions
     * @return string
     */
    public function actions($actions)
    {
        $baseLink = '<a class="btn btn-sm %s" href="%s" title="%s" %s><i class="fa %s"></i></a>';
        $container = '<div class="btn-group">%s</div>';
        $links = '';

        foreach ($actions as $action) {
            $attributes = '';

            if ($action['name'] == 'back') {
                $class = !empty($action['class']) ? $action['class'] :'btn-default';
                $icon = !empty($action['icon']) ? $action['icon'] :'fa-arrow-left';
                $title = !empty($action['title']) ? $action['title'] : $this->translator->trans('back');
                $this->translator->trans('delete');
            } elseif ($action['name'] == 'create') {
                $class = !empty($action['class']) ? $action['class'] :'btn-primary';
                $icon = !empty($action['icon']) ? $action['icon'] :'fa-plus-circle';
                $title = !empty($action['title']) ? $action['title'] : $this->translator->trans('create');
            } elseif ($action['name'] == 'show') {
                $class = !empty($action['class']) ? $action['class'] :'btn-success';
                $icon = !empty($action['icon']) ? $action['icon'] :'fa-info-circle';
                $title = !empty($action['title']) ? $action['title'] : $this->translator->trans('show');
            } elseif ($action['name'] == 'edit') {
                $class = !empty($action['class']) ? $action['class'] :'btn-warning';
                $icon = !empty($action['icon']) ? $action['icon'] :'fa-pencil';
                $title = !empty($action['title']) ? $action['title'] : $this->translator->trans('edit');
            } elseif ($action['name'] == 'delete') {
                $class = !empty($action['class']) ? $action['class'] :'btn-danger';
                $icon = !empty($action['icon']) ? $action['icon'] :'fa-trash-o';
                $title = !empty($action['title']) ? $action['title'] : $this->translator->trans('delete');

            } else {
                $class = !empty($action['class']) ? $action['class'] :'btn-default';
                $icon = !empty($action['icon']) ? $action['icon'] :'fa-square-o';
                $title = !empty($action['title']) ? $action['title'] : $this->translator->trans('button');
            }

            if (!empty($action['attributes']) && is_array($action['attributes'])) {
                $baseAttribute = '%s="%s"';
                foreach ($action['attributes'] as $key => $value) {
                    $attributes .= sprintf($baseAttribute, $key, $value);
                }
            }

            $links .= sprintf($baseLink, $class, $action['href'], $title, $attributes, $icon);
        }

        return sprintf($container, $links);
    }
}