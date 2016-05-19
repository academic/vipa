<?php

namespace Ojs\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublisherApplicationType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'publisherType',
                'entity',
                array(
                    'class' => 'Ojs\JournalBundle\Entity\PublisherTypes',
                    'label' => 'publisher.type',
                )
            )
            ->add('translations', 'a2lix_translations', ['label' => 'publisher.about'])
            ->add('address', 'textarea', ['label' => 'publisher.address'])
            ->add('addressLat', null, ['label' => 'publisher.lat'])
            ->add('addressLong', null, ['label' => 'publisher.lon'])
            ->add('email', 'email', ['label' => 'publisher.email'])
            ->add('fax', null, ['label' => 'publisher.fax'])
            ->add('phone', null, ['label' => 'publisher.phone'])
            ->add('url', 'url', ['label' => 'publisher.url'])
            ->add('wiki', 'url', ['label' => 'publisher.wiki_url'])
            ->add('tags', 'tags', ['label' => 'publisher.tags'])
            ->add('logo', 'jb_crop_image_ajax', array(
                'endpoint' => 'publisher',
                'img_width' => 200,
                'img_height' => 200,
                'crop_options' => array(
                    'aspect-ratio' => 200 / 200,
                    'maxSize' => "[200, 200]"
                )
            ))
            ->add('header', 'jb_crop_image_ajax', array(
                'endpoint' => 'publisher',
                'img_width' => 960,
                'img_height' => 200,
                'crop_options' => array(
                    'aspect-ratio' => 960 / 200,
                    'maxSize' => "[960, 200]"
                )
            ))
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
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_publisherapplication';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\Publisher',
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
