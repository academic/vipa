<?php

namespace Ojs\JournalBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JournalUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'user',
                'autocomplete',
                [
                    'class' => 'Ojs\UserBundle\Entity\User',
                    'label' => 'user',
                    'attr' => [
                        'class' => 'autocomplete',
                        'data-list' => $options['usersEndpoint'],
                        'data-get' => $options['userEndpoint'],
                        "placeholder" => "Type a username",
                    ],
                ]
            )
            ->add(
                'roles',
                'entity',
                [
                    'label' => 'roles',
                    'class' => 'Ojs\UserBundle\Entity\Role',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => false,
                    'attr' => array("class" => "select2-element"),
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('journal_user_role');
                    },
                ]
            );
    }

    /**
     * Returns the name of this type.
     * @return string The name of this type
     */
    public function getName()
    {
        return 'ojs_journalbundle_journaluser';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'usersEndpoint' => '/',
                'userEndpoint' => '/',
            )
        );
    }


}