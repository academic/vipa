<?php

namespace Ojs\UserBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ojs\UserBundle\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserJournalRoleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder->add('user', 'autocomplete', [
            'class' => 'Ojs\UserBundle\Entity\User',
            'attr' => [
                'class' => 'autocomplete',
                'style' => 'width:100%',
                'data-list' => "/api/public/search/user",
                'data-get' => "/api/public/user/get/",
                "placeholder" => "type a username"
            ]
        ])
            ->add('journal', 'autocomplete', [
                'class' => 'Ojs\JournalBundle\Entity\Journal',
                'attr' => [
                    'class' => 'autocomplete',
                    'style' => 'width:100%',
                    'data-list' => "/api/public/search/journal",
                    'data-get' => "/api/public/journal/get/",
                    "placeholder" => "type a journal name"
                ],

            ])
            ->add('role', 'entity', [
                'class' => 'Ojs\UserBundle\Entity\Role',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                    return $er->createQueryBuilder('ujr')
                        ->where('ujr.isSystemRole = 0');
                },
                'attr' => array("class" => "form-control")
                ]
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\UserBundle\Entity\UserJournalRole',
            'user' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_userbundle_userjournalrole';
    }

}
