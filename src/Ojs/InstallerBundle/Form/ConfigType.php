<?php

namespace Ojs\InstallerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('database_driver')
            ->add('database_host')
            ->add('database_port')
            ->add('database_name')
            ->add('database_user')
            ->add('database_password')
            ->add('system_email')
            ->add('mailer_transport')
            ->add('mailer_host')
            ->add('mailer_user')
            ->add('mailer_password')
            ->add('locale')
            ->add('secret')
            ->add('base_host')
            ->add('post_types')
            ->add('elasticsearch_host')
            ->add('mongodb_host')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\InstallerBundle\Entity\Config',
            'attr'=>[
                'novalidate'=>'novalidate'
,'class'=>'form-validate'
            ]
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_installerbundle_config';
    }
}
