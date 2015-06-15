<?php

namespace Ojs\JournalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', ['label' => 'title'])
            ->add('firstName', 'text', ['label' => 'firstname'])
            ->add('lastName', 'text', ['label' => 'lastname'])
            ->add('address', 'text', ['label' => 'address'])
            ->add(
                'country',
                'entity',
                [
                    'label' => 'country',
                    'class' => 'Ojs\LocationBundle\Entity\Country',
                    'attr' => [
                        'class' => "select2-element validate[required]",
                    ],
                ]
            )
            ->add(
                'city',
                'autocomplete',
                [
                    'class' => 'Ojs\LocationBundle\Entity\Province',
                    'label' => 'city',
                    'attr' => [
                        'class' => 'autocomplete',
                        'data-list' => $options['apiRoot']."public/search/province",
                        'data-get' => $options['apiRoot']."public/province/get/",
                        "placeholder" => "type a journal name",
                    ],
                ]
            )
            ->add('phone', 'text', ['label' => 'phone'])
            ->add('fax', 'text', ['label' => 'fax'])
            ->add('email', 'email', ['label' => 'email'])
            ->add(
                'tags',
                'text',
                array(
                    'label' => 'tags',
                    'attr' => [
                        'class' => ' form-control input-xxl',
                        'data-role' => 'tagsinputautocomplete',
                        'placeholder' => 'Comma-seperated tag list',
                        'data-list' => '/api/public/search/tags',
                    ],
                )
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\Contact',
                'apiRoot' => '/',
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
        return 'ojs_journalbundle_contact';
    }
}
