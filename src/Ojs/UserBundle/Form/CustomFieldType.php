<?php

namespace Ojs\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomFieldType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label','text',[
                'attr'=>[
                    'class'=>'validate[required,minSize[2]]'
                ]
            ])
            ->add('value','text',[
                'attr'=>[
                    'class'=>'validate[required,minSize[2]]'
                ]
            ])
            ->add('is_url')
            ->add('user_id', 'hidden', ['attr' => ['value' => $options['user']]]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\UserBundle\Entity\CustomField',
            'user' => 0,
            'csrf_protection'=>false,
            'attr'=>[
                'class'=>'validate-form',
                'novalidate'=>'novalidate'
            ]
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_userbundle_customfieldtype';
    }

}
