<?php

namespace Vipa\CoreBundle\Service\Twig;

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
     * @return \Twig_SimpleFunction[] An array of functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('actions', array($this, 'actions'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Generates action buttons with Bootstrap theming
     * @param array $actions
     * @return string
     */
    public function actions($actions)
    {
        $container = '<div class="action-group btn-group">%s</div>';
        $baseLink = '<a href="%s" class="btn btn-sm %s" title="%s"%s><i class="fa %s"></i></a>';
        $links = '';

        $templates = [
            'back' => [
                'options' => [
                    'class' => 'btn-default',
                    'icon'  => 'fa-arrow-left',
                    'title' => $this->translator->trans('back')
                ],

                'attributes' => [],
            ],

            'show' => [
                'options' => [
                    'class' => 'btn-primary',
                    'icon'  => 'fa-info-circle',
                    'title' => $this->translator->trans('show')
                ],

                'attributes' => [],
            ],

            'create' => [
                'options' => [
                    'class' => 'btn-success',
                    'icon'  => 'fa-plus-circle',
                    'title' => $this->translator->trans('create')
                ],

                'attributes' => [],
            ],

            'edit' => [
                'options' => [
                    'class' => 'btn-warning',
                    'icon'  => 'fa-pencil',
                    'title' => $this->translator->trans('edit')
                ],

                'attributes' => [],
            ],

            'delete' => [
                'options' => [
                    'class' => 'btn-danger',
                    'icon'  => 'fa-trash-o',
                    'title' => $this->translator->trans('delete')
                ],

                'attributes' => [
                    'data-method'   => 'delete',
                    'data-role'     => 'delete',
                ],
            ],
        ];

        foreach ($actions as $name => $parameters) {
            if (array_key_exists('permission', $parameters) && !$parameters['permission']) {
                continue;
            }

            $href = '#';
            $attributes = array();
            $atrributesAsString = '';
            $options = [
                'class' => null,
                'title' => null,
                'icon' => null
            ];

            if (array_key_exists('href', $parameters)) {
                $href = $parameters['href'];
            }

            if (array_key_exists($name, $templates)) {
                $template = array_merge_recursive($templates[$name], $parameters);
                $options = $template['options'];
                $attributes = $template['attributes'];
            }

            foreach ($attributes as $key => $value) {
                $atrributesAsString .= sprintf(' %s="%s"', $key, $value);
            }

            $links .= sprintf(
                $baseLink,
                $href,
                $options['class'],
                $options['title'],
                $atrributesAsString,
                $options['icon']
            );
        }

        return sprintf($container, $links);
    }
}
