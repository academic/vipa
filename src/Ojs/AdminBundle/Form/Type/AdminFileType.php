<?php

namespace Ojs\AdminBundle\Form\Type;

use Ojs\AdminBundle\Entity\AdminFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminFileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('path', 'jb_file_ajax', array('endpoint' => 'files', 'remove_link' => false))
            ->add('tags', 'tags')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => AdminFile::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ));
    }
}
