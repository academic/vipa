<?php

namespace Ojs\JournalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SubmissionFileType
 * @package Ojs\JournalBundle\Form\Type
 */
class SubmissionFileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', 'text', [
                'label' => 'submission_checklist.label'
                ]
            )
            ->add('detail', 'textarea', [
                'label' => 'submission_checklist.detail'
                ]
            )
            ->add(
                'locale',
                'choice',
                [
                    'choices' => $options['languages'],
                ]
            )
            ->add('file', 'jb_file_ajax',
                array(
                    'endpoint' => 'journalCompeting'
                )
            )
            ->add('visible', 'checkbox', [
                'label' => 'submission_checklist.visible'
                ]
            )
            ->add('required', 'checkbox', [
                'label' => 'submission_checklist.required'
                ]
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
                'data_class' => 'Ojs\JournalBundle\Entity\SubmissionFile',
                'cascade_validation' => true,
                'languages' => array(
                    array('tr' => 'Türkçe'),
                    array('en' => 'English'),
                ),
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
        return 'ojs_journalbundle_submissionfile';
    }
}
