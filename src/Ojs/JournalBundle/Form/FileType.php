<?php

namespace Ojs\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FileType extends AbstractType
{
    /**
     * @param  FormBuilderInterface $builder
     * @param  array                $options
     * @return bool
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','hidden',[
                'label'=>'name'
            ])
            ->add('tags', 'text', array(
                    'label' => 'keywords',
                    'attr' => [
                        'class' => ' form-control input-xxl',
                        'data-role' =>  'tagsinput',
                        'placeholder' => 'Comma-seperated tag list'
                    ]
                )
            )
            ->add('path','hidden',[
                'label'=>'path'
            ])
            ->add('mimeType','hidden')
            ->add('size','hidden')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\File',
            'attr' => [
                'novalidate' => 'novalidate', 'class' => 'form-validate',
            ],
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_articlefile';
    }
}
