<?php

namespace Ojs\JournalBundle\Form\Type;

use OkulBilisim\LocationBundle\Form\EventListener\AddCountryFieldSubscriber;
use OkulBilisim\LocationBundle\Form\EventListener\AddProvinceFieldSubscriber;
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
            ->add('address', 'textarea', [
                'required' => false,
                'label' => 'address'
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
                'data_class' => 'Ojs\JournalBundle\Entity\JournalContact',
                'cascade_validation' => true,
                'attr' => [
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
        return 'ojs_journalbundle_journalcontact';
    }
}
