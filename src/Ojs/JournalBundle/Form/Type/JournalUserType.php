<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\Common\Params\CommonParams;
use Ojs\LocationBundle\Form\EventListener\AddCountryFieldSubscriber;
use Ojs\LocationBundle\Form\EventListener\AddProvinceFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class JournalUserType extends AbstractType
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
            ->add('tags', 'tags')
            ->add('avatar', 'hidden')
            ->add('header', 'hidden')
            ->add(
                'journalRoles',
                'entity',
                [
                    'label' => 'journal.roles',
                    'class' => 'Ojs\UserBundle\Entity\Role',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('ujr');
                    },
                    'attr' => array("class" => "select2-element"),
                ]
            )
            ->addEventSubscriber(new AddProvinceFieldSubscriber())
            ->addEventSubscriber(new AddCountryFieldSubscriber('/location/cities/'));
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
                'attr' => [
                    'class' => 'validate-form',
                    'novalidate' => 'novalidate',
                ],
            ]
        );
    }
}
