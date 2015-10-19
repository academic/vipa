<?php

namespace Ojs\CmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileType extends AbstractType
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
            'data_class' => 'Ojs\CmsBundle\Entity\File',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_cmsbundle_file';
    }
}
