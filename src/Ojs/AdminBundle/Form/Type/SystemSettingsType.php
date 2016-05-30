<?php

namespace Ojs\AdminBundle\Form\Type;

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
            ->add('user_registration', 'choice', [
                'choices'   => ['1' => 'on', '0' => 'off'],
                'label'     => 'title.user_new',
                'expanded'  => true,
                'required'  => true
                ]
            )
            ->add('journal_application', 'choice', [
                'choices'   => ['1' => 'on', '0' => 'off'],
                'label'     => 'title.journal_application',
                'expanded'  => true,
                'required'  => true
                ]
            )
            ->add('publisher_application', 'choice', [
                'choices'   => ['1' => 'on', '0' => 'off'],
                    'label' => 'title.publisher_application',
                'expanded'  => true,
                'required'  => true
                ]
            )
            ->add('article_submission', 'choice', [
                'choices'   => ['1' => 'on', '0' => 'off'],
                'label'     => 'title.submission_new',
                'expanded'  => true,
                'required'  => true
                ]
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
        // It's all dark and lonely here.
    }
}
