<?php

namespace Ojs\AdminBundle\Form\Type;

use Ojs\LocationBundle\Form\EventListener\AddCountryFieldSubscriber;
use Ojs\LocationBundle\Form\EventListener\AddProvinceFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class InstitutionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $institutionId = $options['data']->getId() ? $options['data']->getId(): null;
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
            ->add(
                'theme',
                'entity',
                array(
                    'label' => 'theme',
                    'class' => 'Ojs\JournalBundle\Entity\InstitutionTheme',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) use ($institutionId) {
                        $query = $er->createQueryBuilder('t');
                        if(is_null($institutionId)){
                            $query->where('t.isPublic IS NULL OR t.isPublic = TRUE');
                        }else{
                            $query->where('t.isPublic IS NULL OR t.isPublic = TRUE OR t.institution = :institutionId')
                            ->setParameter('institutionId', $institutionId);
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
                    'class' => 'Ojs\JournalBundle\Entity\InstitutionDesign',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                            ->where('t.isPublic IS NULL OR t.isPublic = TRUE');
                    },
                    'error_bubbling'=>true,
                )
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
                'institutionsEndPoint' => '/',
                'institutionEndPoint' => '/',
                'institution' => null,
                'attr' => [
                    'class' => 'validate-form',
                ],
            )
        );
    }
}
