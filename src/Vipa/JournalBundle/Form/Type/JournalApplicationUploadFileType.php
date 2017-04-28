<?php

namespace Vipa\JournalBundle\Form\Type;

use Vipa\JournalBundle\Entity\JournalApplicationUploadFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class JournalApplicationUploadFileType
 * @package Vipa\JournalBundle\Form\Type
 */
class JournalApplicationUploadFileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'jb_file_ajax',
                array(
                    'endpoint' => 'submissionfiles'
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
                'data_class' => JournalApplicationUploadFile::class,
                'cascade_validation' => true,
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
