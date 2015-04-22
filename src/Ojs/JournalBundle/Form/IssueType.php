<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ojs\UserBundle\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ojs\UserBundle\Entity\User;

class IssueType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $journal = $options['journal'];
        $user = $options['user'];
        $builder
                ->add('journal', 'entity', array(
                    'attr' => array('class' => ' form-control select2-element'),
                    'label' => 'journal',
                    'class' => 'Ojs\JournalBundle\Entity\Journal',
                    'query_builder' =>
                    function (EntityRepository $er) use ($user, $journal) {
                /** @var User $user $qb */
                $qb = $er->createQueryBuilder('j');
                foreach ($user->getRoles() as $role) {
                    /** @var Role $role */
                    if ($role->getRole() == 'ROLE_SUPER_ADMIN') {
                        return $qb;
                        break;
                    }
                }
                if ($journal) {
                    $qb->where(
                            $qb->expr()->eq('j.id', ':journal')
                    )->setParameter('journal', $journal);
                }
                $qb
                ->join('j.userRoles', 'user_role', 'WITH', 'user_role.user=:user')
                ->setParameter('user', $user);
                return $qb;
            }
                        )
                )
                ->add('volume', 'text', array('label' => 'volume'))
                ->add('number', 'text', array('label' => 'number'))
                ->add('title', 'text', array('label' => 'title'))
                ->add('special', 'checkbox', array('label' => 'special'))
                ->add('description', 'text', array('label' => 'description'))
                ->add('year', 'text', array('label' => 'year'))
                ->add('datePublished', 'collot_datetime', array(
                    'date_format' => 'yyyy-MM-dd',
                        )
                )
                ->add('cover', 'hidden')
                ->add('header', 'hidden')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\Issue',
            'user' => null,
            'journal' => null,
            'attr' => [
                'novalidate' => 'novalidate'
                , 'class' => 'form-validate'
            ]
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
