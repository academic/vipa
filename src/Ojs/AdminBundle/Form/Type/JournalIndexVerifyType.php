<?php

namespace Ojs\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalIndexVerifyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('index', null, [
                'disabled' => true,
                'required' => false,
            ])
            ->add('link', null, [
                'disabled' => true,
                'required' => false,
            ])
            ->add('verified', 'checkbox', [
                'required' => false,
            ])
            ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\JournalIndex',
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'ojs_admin_contact';
    }
}
