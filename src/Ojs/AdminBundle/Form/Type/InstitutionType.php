<?php

namespace Ojs\AdminBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\InstitutionRepository;
use Ojs\LocationBundle\Form\EventListener\AddCountryFieldSubscriber;
use Ojs\LocationBundle\Form\EventListener\AddProvinceFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstitutionType extends AbstractType
{
    private $selfId;

    /**
     * InstitutionType constructor.
     * @param $selfId
     */
    public function __construct($selfId = null)
    {
        $this->selfId = $selfId;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $selfId = $this->selfId;
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
            ->add('translations', 'a2lix_translations')
            ->add(
                'institutionType',
                'entity',
                [
                    'label' => 'institution.type',
                    'class' => 'Ojs\JournalBundle\Entity\PublisherTypes',
                    'attr' => [
                        'class' => "validate[required]",
                    ],
                ]
            )
            ->add(
                'parent',
                'entity',
                [
                    'label' => 'parent',
                    'class' => 'Ojs\JournalBundle\Entity\Institution',
                    'attr' => [
                        'class' => "select2-element",
                    ],
                    'placeholder' => 'none',
                    'empty_data'  => null,
                    'query_builder' => function (InstitutionRepository $repository) use ($selfId) {
                        if ($selfId != null) {
                            return $repository
                                ->createQueryBuilder('institution')
                                ->andWhere('institution.id != :selfId')
                                ->setParameter('selfId', $selfId);
                        }
                    }
                ]
            )
            ->add('address', 'textarea', ['label' => 'address'])
            ->add('phone', 'text', ['label' => 'phone'])
            ->add('fax', 'text', ['label' => 'fax'])
            ->add('email', 'email', ['label' => 'email'])
            ->add('wiki')
            ->add('tags', 'tags')
            ->add('logo', 'jb_crop_image_ajax', array(
                'endpoint' => 'institution',
                'img_width' => 200,
                'img_height' => 200,
                'crop_options' => array(
                    'aspect-ratio' => 200 / 200,
                    'maxSize' => "[200, 200]"
                )
            ))
            ->add('domain')
            ->add('header', 'jb_crop_image_ajax', array(
                'endpoint' => 'institution',
                'img_width' => 960,
                'img_height' => 200,
                'crop_options' => array(
                    'aspect-ratio' => 960 / 200,
                    'maxSize' => "[960, 200]"
                )
            ))
            ->add('addressLat', 'text', ['label' => 'addressLat', 'attr' => ['data-id' => 'addressLat']])
            ->add('addressLong', 'text', ['label' => 'addressLong', 'attr' => ['data-id' => 'addressLong']])
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
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\Institution',
                'cascade_validation' => true,
                'institution' => null,
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'validate-form',
                ],
            )
        );
    }
}
