<?php

namespace Vipa\JournalBundle\Form\Type;

use Vipa\JournalBundle\Entity\JournalContact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationJournalContactType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contactType', null, ['label' => 'contact.type'])
            ->add('fullName', 'text', ['label' => 'namesurname'])
            ->add('phone', 'text', [
                'label' => 'phone',
                'required' => false
                ]
            )
            ->add('email', EmailType::class, ['label' => 'email'])
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
