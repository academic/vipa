<?php

namespace Vipa\InstallerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
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
            ->add('mongodb_host');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Vipa\InstallerBundle\Entity\Config',
                'attr' => [
                    'novalidate' => 'novalidate',
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
        return 'vipa_installerbundle_config';
    }
}
