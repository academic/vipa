<?php

namespace Ojs\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('fullName', 'text', ['label' => 'fullname'])
            ->add('address', 'textarea')
            ->add('phone', 'text', ['label' => 'phone'])
            ->add('email', 'email', ['label' => 'email'])
            ->add('tags', 'tags')
            ->add('contactType')
            ->add(
                'journal',
                'tetranz_select2entity',
                [
                    'remote_route' => 'ojs_admin_journal_autocomplete',
                    'class' => 'Ojs\JournalBundle\Entity\Journal',
                    'label' => 'journal',
                    'attr' => [
                        'class' => 'select2-element',
                    ]
                ]
            )
            ->add('institution', null, ['label' => 'institution'])
            ->add('country', 'entity', array(
                'class'         => 'BulutYazilim\LocationBundle\Entity\Country',
                'required'      => false,
                'label'         => 'Country',
                'empty_value'   => 'Select Country',
                'attr'          => array(
                    'class' => 'select2-element',
                ),
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\JournalContact',
                'validation_groups' => 'admin',
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
