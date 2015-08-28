<?php

namespace Ojs\CmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', 'a2lix_translations', array(
                'fields' => array(
                    'title' => [],
                    'body' => array(
                        'field_type' => 'ace_editor',
                        'label' => 'content',
                        'wrapper_attr' => array(),
                        'width' => 700,
                        'height' => 200,
                        'font_size' => 12,
                        'mode' => 'ace/mode/html',
                        'theme' => 'ace/theme/chrome',
                        'tab_size' => null,
                        'read_only' => null,
                        'use_soft_tabs' => null,
                        'use_wrap_mode' => null,
                        'show_print_margin' => null,
                        'highlight_active_line' => null
                    )
                )
            )
        )->add('tags', 'tags');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\CmsBundle\Entity\Page',
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_cms_page';
    }
}
