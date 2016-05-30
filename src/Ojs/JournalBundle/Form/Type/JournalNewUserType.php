<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\JournalBundle\Entity\SubjectRepository;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalNewUserType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                'text',
                [
                    'label' => 'username',
                    'attr' => [
                        'class' => 'validate[required]',
                    ],
                ]
            )
            ->add(
                'password',
                'password',
                [
                    'label' => 'password',
                    'attr' => [

                        'class' => 'validate[minSize[6]]',
                    ],
                ]
            )
            ->add(
                'email',
                'email',
                [
                    'label' => 'email',
                    'attr' => [
                        'class' => 'validate[required,custom[email]]',
                    ],
                ]
            )
            ->add('title', null, [
                'label' => 'user.title',
                'required' => false
                ]
            )
            ->add(
                'firstName',
                'text',
                [
                    'label' => 'firstname',
                    'attr' => [
                        'class' => 'validate[required]',
                    ],
                ]
            )
            ->add(
                'lastName',
                'text',
                [
                    'label' => 'lastname',
                    'attr' => [
                        'class' => 'validate[required]',
                    ],
                ]
            )
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
            ->add('tags', 'tags', ['label' => 'tags'])
            ->add('avatar', 'jb_crop_image_ajax', array(
                'endpoint' => 'user',
                'required' => false,
                'img_width' => 200,
                'img_height' => 200,
                'crop_options' => array(
                    'aspect-ratio' => 200 / 200,
                    'maxSize' => "[200, 200]"
                ),
                'label' => 'Avatar'
            ))->add('country', 'entity', array(
                'class'         => 'BulutYazilim\LocationBundle\Entity\Country',
                'required'      => false,
                'label' => 'country',
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
            [
                'data_class' => User::class,
                'cascade_validation' => true,
                'validation_groups' => ['journal_user'],
                'attr' => [
                    'class' => 'validate-form',
                ],
            ]
        );
    }
}
