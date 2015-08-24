<?php

namespace Ojs\AdminBundle\Form\Type;

use Ojs\LocationBundle\Form\EventListener\AddCountryFieldSubscriber;
use Ojs\LocationBundle\Form\EventListener\AddProvinceFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('translations', 'a2lix_translations', ['label' => 'institution.about'])
            ->add('address', 'textarea', ['label' => 'institution.address'])
            ->add('addressLat', null, ['label' => 'institution.lat'])
            ->add('addressLong', null, ['label' => 'institution.lon'])
            ->add('email', 'email', ['label' => 'institution.email'])
            ->add('fax', null, ['label' => 'institution.fax'])
            ->add('phone', null, ['label' => 'institution.phone'])
            ->add('url', 'url', ['label' => 'institution.url'])
            ->add('wiki', 'url', ['label' => 'institution.wiki_url'])
            ->add('tags', 'tags', ['label' => 'institution.tags'])
            ->add('logo', 'jb_crop_image_ajax', array(
                'endpoint' => 'institution',
                'img_width' => 200,
                'img_height' => 200,
                'crop_options' => array(
                    'aspect-ratio' => 200 / 200,
                    'maxSize' => "[200, 200]"
                )
            ))
            ->add('header', 'jb_crop_image_ajax', array(
                'endpoint' => 'institution',
                'img_width' => 960,
                'img_height' => 200,
                'crop_options' => array(
                    'aspect-ratio' => 960 / 200,
                    'maxSize' => "[960, 200]"
                )
            ))
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
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\Institution',
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
