<?php

namespace Ojs\JournalBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalUserType extends AbstractType implements FormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'user',
                'select2entity',
                [
                    'label' => 'user',
                    'class' => 'Ojs\UserBundle\Entity\User',
                    'remote_route' => 'api_get_users'
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'usersEndpoint' => '/',
                'userEndpoint' => '/',
                'validation_groups' => array('create'),
            )
        );
    }
}
