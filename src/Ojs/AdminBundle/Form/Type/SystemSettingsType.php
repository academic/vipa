<?php

namespace Ojs\AdminBundle\Form\Type;

use Norzechowicz\AceEditorBundle\Form\Extension\AceEditor\Type\AceEditorType;
use Ojs\AdminBundle\Entity\SystemSetting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemSettingsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userRegistrationActive', 'choice', [
                'choices'   => ['1' => 'on', '0' => 'off'],
                'label'     => 'title.user_new',
                'expanded'  => true,
                'required'  => true
                ]
            )
            ->add('journalApplicationActive', 'choice', [
                'choices'   => ['1' => 'on', '0' => 'off'],
                'label'     => 'title.journal_application',
                'expanded'  => true,
                'required'  => true
                ]
            )
            ->add('publisherApplicationActive', 'choice', [
                'choices'   => ['1' => 'on', '0' => 'off'],
                    'label' => 'title.publisher_application',
                'expanded'  => true,
                'required'  => true
                ]
            )
            ->add('articleSubmissionActive', 'choice', [
                'choices'   => ['1' => 'on', '0' => 'off'],
                'label'     => 'title.submission_new',
                'expanded'  => true,
                'required'  => true
                ]
            )
            ->add(
                'systemFooterScript',
                AceEditorType::class,
                array(
                    'width' => 700,
                    'height' => 200,
                    'font_size' => 12,
                    'mode' => 'ace/mode/javascript', // every single default mode must have ace/mode/* prefix
                    'theme' => 'ace/theme/chrome', // every single default theme must have ace/theme/* prefix
                )
            )
            ->add('submit', 'submit', [
                'label'     => 'update',
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SystemSetting::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ));
    }
}
