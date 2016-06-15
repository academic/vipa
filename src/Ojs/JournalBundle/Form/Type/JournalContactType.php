<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\JournalBundle\Entity\JournalContact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalContactType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contactType', null, ['label' => 'contact.type'])
            ->add('fullName', 'text', ['label' => 'fullname'])
            ->add('title')
            ->add('address', 'purified_textarea', [
                'required' => false,
                'label' => 'address',
                'attr' => array('class' => ' form-control wysihtml5'),
            ])
            ->add('phone', 'text', [
                'label' => 'phone',
                'required' => false
                ]
            )
            ->add('email', 'email', ['label' => 'email'])
            ->add('institution', null, ['label' => 'institution'])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => JournalContact::class,
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
