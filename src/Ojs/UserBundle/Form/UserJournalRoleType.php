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
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
                ->add('user','entity',[
                    'class'=>'Ojs\UserBundle\Entity\User',
                    'attr' => array('class' => 'select2-element', 'style' => 'width:100%')

                ])
                ->add('journal','entity',[
                    'class'=>'Ojs\JournalBundle\Entity\Journal',
                    'property'=>'title',
                    'attr' => array('class' => 'select2-element', 'style' => 'width:100%'),
                    'query_builder' => function(EntityRepository $er)use($user) {
                        /** @var User $user $qb */
                        $qb = $er->createQueryBuilder('j');
                        foreach ($user->getRoles() as $role) {
                            /** @var Role $role */
                            if ($role->getRole() == 'ROLE_SUPER_ADMIN') {
                                return $qb;
                                break;
                            }
                        }

                        $qb
                            ->join('j.userRoles', 'user_role', 'WITH', 'user_role.user=:user')
                            ->setParameter('user', $user)
                        ;
                        return $qb;
                    }
                ])
                ->add('role', 'entity', array(
                    'class' => 'Ojs\UserBundle\Entity\Role',
                    'property' => 'name',
                    'multiple' => false,
                    'expanded' => false,
                    'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('ujr')
                        ->where('ujr.isSystemRole = 0');
            },
                    'attr' => array("class" => "form-control"))
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\UserBundle\Entity\UserJournalRole',
            'user'=>null
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
