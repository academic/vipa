<?php

namespace Ojs\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BlockType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('type','choice',[
                'label'=>'Type',
                'choices'=>[
                    'html'=>'HTML Content',
                    'link'=>'Link List'
                ]
            ])
            ->add('content','textarea',[
                'label'=>'Content',
                'required'=>false
            ])
            ->add('object_id','hidden',['data'=>$options['object_id']])
            ->add('object_type','hidden',['data'=>$options['object_type']])
            ->add('color','choice',['label'=>'Block Color','choices'=>[
                'default'=>'Grey',
                'primary'=>'Blue',
                'success'=>'Green',
                'info'=>'Light Blue',
                'warning'=>'Yellow',
                'danger'=>'Red',

            ]])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\SiteBundle\Entity\Block',
            'object_id'=>null,
            'object_type'=>null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_sitebundle_block';
    }
}
