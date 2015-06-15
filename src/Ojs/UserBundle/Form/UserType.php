<?php

namespace Ojs\UserBundle\Form;

use Ojs\Common\Params\CommonParams;
use Ojs\LocationBundle\Helper\FormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
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
            ->add('title', 'text', ['label' => 'user.title'])
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
            ->add('isActive', 'checkbox', ['label' => 'user.isActive'])
            ->add(
                'status',
                'choice',
                [
                    'label' => 'status',
                    'choices' => CommonParams::$userStatusArray,
                ]
            )
            ->add(
                'subjects',
                'entity',
                array(
                    'label' => 'subjects',
                    'class' => 'Ojs\JournalBundle\Entity\Subject',
                    'property' => 'subject',
                    'multiple' => true,
                    'expanded' => false,
                    'attr' => array('class' => 'select2-element', 'style' => 'width:100%'),
                    'required' => false,
                )
            )
            ->add(
                'tags',
                'text',
                array(
                    'label' => 'tags',
                    'attr' => [
                        'class' => ' form-control input-xxl',
                        'data-role' => 'tagsinputautocomplete',
                        'placeholder' => 'Comma-seperated tag list',
                        'data-list' => '/api/public/search/tags',
                    ],
                )
            )
            ->add('avatar', 'hidden')
            ->add('header', 'hidden')
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
        /** @var FormHelper $helper */
        $helper = $options['helper'];
        $helper->addCityField($builder, 'Ojs\UserBundle\Entity\User');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_userbundle_user';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Ojs\UserBundle\Entity\User',
                'helper' => null,
                'attr' => [
                    'class' => 'validate-form',
                    'novalidate' => 'novalidate',
                ],
            ]
        );
    }
}
