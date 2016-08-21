<?php

namespace Ojs\UserBundle\Form\Type;

use Ojs\JournalBundle\Entity\SubjectRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateUserType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', [
                'label' => 'user',
                'required' => true
            ])
            ->add('email', 'email', [
                'label' => 'email',
                'required' => true
            ])
            ->add(
                'firstName',
                'text',
                [
                    'required' => true,
                    'attr' => [
                        'label' => 'firstname',
                    ],
                ]
            )
            ->add(
                'title',
                null,
                [
                    'label' => 'user.title',
                    'required' => false,
                ]
            )
            ->add(
                'lastName',
                'text',
                [
                    'required' => true,
                    'attr' => [
                        'label' => 'lastname',
                    ],
                ]
            )
            ->add('about')
            ->add('url')
            ->add(
                'subjects',
                'entity',
                array(
                    'class' => 'OjsJournalBundle:Subject',
                    'multiple' => true,
                    'required' => true,
                    'property' => 'indentedSubject',
                    'label' => 'user.subjects',
                    'attr' => [
                        'style' => 'height: 200px'
                    ],
                    'query_builder' => function(SubjectRepository $er) {
                        return $er->getChildrenQueryBuilder(null, null, 'root', 'asc', false);
                    }
                )
            )
            ->add('avatar', 'jb_crop_image_ajax', array(
                'endpoint' => 'user',
                'img_width' => 200,
                'img_height' => 200,
                'required' => false,
                'crop_options' => array(
                    'aspect-ratio' => 200 / 200,
                    'maxSize' => "[200, 200]"
                )
            ))
            ->add(
                'privacy',
                'checkbox',
                [
                    'label' => 'user.hide_account',
                    'required' => false,
                ]
            )
            ->add('country', 'entity', array(
                'class'         => 'BulutYazilim\LocationBundle\Entity\Country',
                'required'      => false,
                'label'         => 'Country',
                'empty_value'   => 'Select Country',
                'attr'          => array(
                    'class' => 'select2-element',
                ),
            ))
            ->add('institution', null, [
                'attr' => [
                    'class' => 'institution'
                ],
                'label' => 'author.institution'
            ])
            ->add('institutionNotListed', null, [
                'attr' => [
                    'class' => 'institutionNotListed'
                ],
                'label' => 'author.institution_not_listed'
            ])
            ->add('institutionName', null, [
                'attr' => [
                    'class' => 'institutionName'
                ],
                'label' => 'institute.name'
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'         => 'Ojs\UserBundle\Entity\User',
                'cascade_validation' => true,
                'attr'               => [
                    'class' => 'validate-form',
                ],
                'validation_groups'  => 'editProfile',
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_userbundle_updateuser';
    }
}
