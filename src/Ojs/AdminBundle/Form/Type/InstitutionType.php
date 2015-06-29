<?php

namespace Ojs\AdminBundle\Form\Type;

use Ojs\LocationBundle\Form\EventListener\AddCountryFieldSubscriber;
use Ojs\LocationBundle\Form\EventListener\AddProvinceFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstitutionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', 'a2lix_translations_gedmo',[
                'translatable_class' => 'Ojs\JournalBundle\Entity\Institution'
            ])
            ->add(
                'name',
                'text',
                [
                    'label' => 'name',
                    'required' => true,
                    'attr' => [
                        'class' => "validate[required]",
                    ],
                ]
            )
            ->add(
                'slug',
                'text',
                [
                    'label' => 'institution.slug',
                    'attr' => [
                        'class' => "validate[required]",
                    ],
                ]
            )
            ->add(
                'institution_type',
                'entity',
                [
                    'label' => 'institutiontype',
                    'class' => 'Ojs\JournalBundle\Entity\InstitutionTypes',
                    'attr' => [
                        'class' => "validate[required]",
                    ],
                ]
            )
            ->add(
                'parent',
                'autocomplete',
                [
                    'class' => 'Ojs\JournalBundle\Entity\Institution',
                    'attr' => [
                        'class' => 'autocomplete',
                        'style' => 'width:100%',
                        'data-list' => $options['institutionsEndPoint'],
                        'data-get' => $options['institutionEndPoint'],
                        "placeholder" => "type a institution name",
                    ],
                ]
            )
            ->add('address', 'textarea', ['label' => 'address'])
            ->add('addressLat', 'text', ['label' => 'addressLat'])
            ->add('addressLong', 'text', ['label' => 'addressLong'])
            ->add('phone', 'text', ['label' => 'phone'])
            ->add('fax', 'text', ['label' => 'fax'])
            ->add('email', 'email', ['label' => 'email'])
            ->add('wiki')
            ->add('tags', 'tags')
            ->add('logo', 'hidden')
            ->add('header', 'hidden')
            ->add(
                'verified',
                'checkbox',
                [
                    'label' => 'verified',
                    'attr' => [
                        'class' => "checkbox",
                    ],
                ]
            )
            ->addEventSubscriber(new AddProvinceFieldSubscriber())
            ->addEventSubscriber(new AddCountryFieldSubscriber('/location/cities/'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_institution';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\Institution',
                'institutionsEndPoint' => '/',
                'institutionEndPoint' => '/',
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'validate-form',
                ],
            )
        );
    }
}
