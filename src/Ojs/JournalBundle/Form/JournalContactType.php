<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JournalContactType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $journal = $options['journal_id'];
        $user = $options['user'];
        $builder
                ->add('journal', 'entity', array(
                    'attr' => array('class' => ' form-control'),
                    'class' => 'Ojs\JournalBundle\Entity\Journal',
                    'query_builder' => function(EntityRepository $er)use($user, $journal) {
                $qb = $er->createQueryBuilder('j');
                if ($user && !$user->hasRole('ROLE_SUPER_ADMIN')) {
                    // if user is super admin get all journals
                    $qb
                    ->join('j.userRoles', 'user_role', 'WITH', 'user_role.user=:user')
                    ->setParameter('user', $user);
                }
                if ($journal) {
                    $qb
                    ->where($qb->expr()->eq('j.journalId', ':journal'))
                    ->setParameter('journal', $journal);
                }
                return $qb;
            }
                ))
                ->add('contact', 'entity', array(
                    'attr' => array('class' => ' form-control'),
                    'class' => 'Ojs\JournalBundle\Entity\Contact')
                )
                ->add('contactType')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\JournalContact',
            'user' => null,
            'journal_id' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_journalcontact';
    }

}
