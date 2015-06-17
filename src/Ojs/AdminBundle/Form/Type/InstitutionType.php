<?php

namespace Ojs\AdminBundle\Form\Type;

use Ojs\LocationBundle\Helper\FormHelper;
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
        /** @var FormHelper $helper */
        $helper = $options['helper'];
        $builder
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
            ->add('about', 'textarea', ['label' => 'about'])
            ->add('address', 'textarea', ['label' => 'address'])
            ->add('addressLat', 'text', ['label' => 'addressLat'])
            ->add('addressLong', 'text', ['label' => 'addressLong'])
            ->add('phone', 'text', ['label' => 'phone'])
            ->add('fax', 'text', ['label' => 'fax'])
            ->add('email', 'email', ['label' => 'email'])
            ->add('url', 'url', ['label' => 'url'])
            ->add('wiki')
            ->add(
                'tags',
                'text',
                array(
                    'label' => 'tags',
                    'attr' => [
                        'class' => ' form-control input-xxl',
                        'data-role' => 'tagsinputautocomplete',
                        'placeholder' => 'Comma-seperated tag list',
                        'data-list' => $options['tagEndPoint'],
                    ],
                )
            )
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
            ->add(
                'country',
                'entity',
                [
                    'label' => 'country',
                    'class' => 'Ojs\LocationBundle\Entity\Country',
                    'attr' => [
                        'class' => 'select2-element  bridged-dropdown',
                        'data-to' => '#'.$this->getName().'_city',
                    ],
                ]
            );
        $helper->addCityField($builder, 'Ojs\JournalBundle\Entity\Institution');
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
                'helper' => null,
                'tagEndPoint' => '/',
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
