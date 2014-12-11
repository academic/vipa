<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ojs\UserBundle\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ojs\UserBundle\Entity\User;
class IssueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
            ->add('journal', 'entity', array(
                'attr' => array('class' => ' form-control select2'),
                'class' => 'Ojs\JournalBundle\Entity\Journal',
                'query_builder' => function (EntityRepository $er) use ($user) {
                    /** @var User $user $qb */
                    $qb = $er->createQueryBuilder('j');
                    foreach ($user->getRoles() as $role) {
                        /** @var Role $role */
                        if($role->getRole()=='ROLE_SUPER_ADMIN') {
                            return $qb;
                            break;
                        }
                    }
                    $qb
                        ->join('j.userRoles', 'user_role', 'WITH', 'user_role.user=:user')
                        ->setParameter('user', $user);
                    return $qb;
                }
            ))->add('volume')
            ->add('number')
            ->add('title')
            ->add('special')
            ->add('special')
            ->add('description')
            ->add('year')
            ->add('datePublished')
            ->add('cover','hidden');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\Issue',
            'user' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_issue';
    }

}
