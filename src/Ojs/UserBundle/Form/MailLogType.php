<?php

namespace Ojs\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MailLogType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mailObject')
            ->add('recipientEmail');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\UserBundle\Entity\MailLog',
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
        return 'ojs_userbundle_maillog';
    }
}
