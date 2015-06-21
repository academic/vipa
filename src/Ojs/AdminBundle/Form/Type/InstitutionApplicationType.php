<?php

namespace Ojs\AdminBundle\Form\Type;

use Ojs\LocationBundle\Form\EventListener\AddCountryFieldSubscriber;
use Ojs\LocationBundle\Form\EventListener\AddProvinceFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstitutionApplicationType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'institution.name'])
            ->add(
                'institution_type',
                'entity',
                array(
                    'class' => 'Ojs\JournalBundle\Entity\InstitutionTypes',
                    'label' => 'institution.type',
                )
            )
            ->add('about', null, ['label' => 'institution.about'])
            ->add('address', null, ['label' => 'institution.address'])
            ->add('addressLat', null, ['label' => 'institution.lat'])
            ->add('addressLong', null, ['label' => 'institution.lon'])
            ->add('email', null, ['label' => 'institution.email'])
            ->add('fax', null, ['label' => 'institution.fax'])
            ->add('phone', null, ['label' => 'institution.phone'])
            ->add('url', null, ['label' => 'institution.url'])
            ->add('wiki', null, ['label' => 'institution.wiki_url'])
            ->add('tags', null, ['label' => 'institution.tags'])
            ->add('logo', 'hidden')
            ->add('header', 'hidden')
            ->addEventSubscriber(new AddProvinceFieldSubscriber())
            ->addEventSubscriber(new AddCountryFieldSubscriber('/location/cities/'))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_institutionapplication';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\Institution',
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
