<?php

namespace Vipa\AdminBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Vipa\JournalBundle\Entity\Publisher;
use Vipa\JournalBundle\Entity\PublisherRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublisherType extends AbstractType
{
    private $selfId;

    /**
     * PublisherType constructor.
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
        $publisherId = null;
        if(isset($options['data'])){
            $publisherId = $options['data']->getId() ? $options['data']->getId() : null;
        }
        $builder
            ->add('translations', 'a2lix_translations', [
                'fields' => [
                    'name' => [
                        'required' => true,
                    ],
                    'about' => [
                        'required' => false,
                    ]
                ]
            ])
            ->add(
                'slug',
                'text',
                [
                    'label' => 'publisher.slug',
                    'required' => true,
                ]
            )
            ->add(
                'publisherType',
                'entity',
                [
                    'required' => true,
                    'label' => 'publishertype',
                    'class' => 'Vipa\JournalBundle\Entity\PublisherTypes',
                    'attr' => [
                        'class' => "validate[required]",
                    ],
                ]
            )
            ->add(
                'status',
                'choice',
                [
                    'required' => true,
                    'label' => 'status',
                    'choices' => Publisher::$statuses,
                ]
            )
            ->add(
                'parent',
                'entity',
                [
                    'label' => 'parent',
                    'required' => false,
                    'class' => 'Vipa\JournalBundle\Entity\Publisher',
                    'attr' => [
                        'class' => "select2-element",
                    ],
                    'placeholder' => 'none',
                    'empty_data'  => null,
                    'query_builder' => function (PublisherRepository $repository) use ($selfId) {
                        $query = $repository->createQueryBuilder('publisher');
                        if ($selfId !== null) {
                            return $query
                                ->andWhere('publisher.id != :selfId')
                                ->setParameter('selfId', $selfId);
                        }
                        return $query;
                    }
                ]
            )
            ->add(
                'theme',
                'entity',
                array(
                    'label' => 'publisher.theme',
                    'class' => 'Vipa\JournalBundle\Entity\PublisherTheme',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) use ($publisherId) {
                        $query = $er->createQueryBuilder('t');
                        if (is_null($publisherId)) {
                            $query->where('t.public IS NULL OR t.public = TRUE');
                        }else{
                            $query->where('t.public IS NULL OR t.public = TRUE OR t.publisher = :publisherId')
                                ->setParameter('publisherId', $publisherId);
                        }
                        return $query;
                    },
                    'error_bubbling'=>true,
                )
            )
            ->add(
                'design',
                'entity',
                array(
                    'label' => 'design',
                    'class' => 'Vipa\JournalBundle\Entity\PublisherDesign',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) use ($publisherId) {
                        $query = $er->createQueryBuilder('t');
                        if (is_null($publisherId)) {
                            $query->where('t.public IS NULL OR t.public = TRUE');
                        }else{
                            $query->where('t.public IS NULL OR t.public = TRUE OR t.publisher = :publisherId')
                                ->setParameter('publisherId', $publisherId);
                        }
                        return $query;
                    },
                    'error_bubbling'=>true,
                )
            )
            ->add('address', 'textarea', [
                'required' => false,
                'label' => 'address',
                ]
            )
            ->add('phone', 'text', [
                'label' => 'phone',
                'required' => false,
                ]
            )
            ->add('fax', 'text', [
                'label' => 'fax',
                'required' => false,
                ]
            )
            ->add('email', 'email', [
                'label' => 'email',
                'required'=> false,
                ]
            )
            ->add('wiki', null, [
                'required'=> false,
            ])
            ->add('tags', 'tags', [
                'required'=> false,
            ])
            ->add('logo', 'jb_crop_image_ajax', array(
                'endpoint' => 'publisher',
                'required'=> false,
                'img_width' => 200,
                'img_height' => 200,
                'crop_options' => array(
                    'aspect-ratio' => 200 / 200,
                    'maxSize' => "[200, 200]"
                )
            ))
            ->add('domain', null, [
                'required'=> false,
            ])
            ->add('header', 'jb_crop_image_ajax', array(
                'endpoint' => 'publisher',
                'required'=> false,
                'img_width' => 960,
                'img_height' => 200,
                'crop_options' => array(
                    'aspect-ratio' => 960 / 200,
                    'maxSize' => "[960, 200]"
                )
            ))
            ->add(
                'verified',
                'checkbox',
                [
                    'required' => false,
                    'label' => 'verified',
                    'attr' => [
                        'class' => "checkbox",
                    ],
                ]
            )
            ->add('addressLat', 'text', [
                'label' => 'addressLat',
                'required'=> false,
                'attr' => [
                    'data-id' => 'addressLat'
                ]
            ])
            ->add('addressLong', 'text', [
                'label' => 'addressLong',
                'required'=> false,
                'attr' => [
                    'data-id' => 'addressLong'
                ]
            ])
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
                'data_class' => Publisher::class,
                'cascade_validation' => true,
                'publisher' => null,
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'validate-form',
                ],
            )
        );
    }
}
