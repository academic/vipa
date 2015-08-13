<?php

namespace Ojs\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class InstitutionThemeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'institution',
                'entity',
                array(
                    'label' => 'institution',
                    'class' => 'Ojs\JournalBundle\Entity\Institution',
                    'property' => 'name',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'error_bubbling'=>true,
                )
            )
            ->add('title', 'text', [
                    'label' => 'Title'
                ]
            )
            ->add('isPublic', 'checkbox', [
                    'label' => 'ojs.is_public'
                ]
            )
            ->add('css', 'ace_editor', array(
                    'wrapper_attr' => array(), // aceeditor wrapper html attributes.
                    'width' => 700,
                    'height' => 200,
                    'font_size' => 12,
                    'mode' => 'ace/mode/css', // every single default mode must have ace/mode/* prefix
                    'theme' => 'ace/theme/twilight', // every single default theme must have ace/theme/* prefix
                    'tab_size' => null,
                    'read_only' => null,
                    'use_soft_tabs' => null,
                    'use_wrap_mode' => null,
                    'show_print_margin' => null,
                    'highlight_active_line' => null
                )
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\InstitutionTheme',
                'attr' => [
                    'novalidate' => 'novalidate',
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
        return 'ojs_adminbundle_institution_theme';
    }
}
