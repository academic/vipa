<?php

namespace Ojs\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JournalSectionType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $journal = $options['journal'];
        $user = $options['user'];
        $builder
                ->add('title')
                ->add('allowIndex')
                ->add('hideTitle')
                ->add('journal', 'entity', array(
                    'attr' => array('class' => 'select2-element'),
                    'class' => 'Ojs\JournalBundle\Entity\Journal',
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er)use($user, $journal) {
                $qb = $er->createQueryBuilder('j');
                if ($user && !$user->hasRole('ROLE_SUPER_ADMIN')) {
                    // if user is super admin get all journals
                    $qb
                    ->join('j.userRoles', 'user_role', 'WITH', 'user_role.user=:user')
                    ->setParameter('user', $user);
                }
                if ($journal) {
                    $qb
                    ->where($qb->expr()->eq('j.id', ':journal'))
                    ->setParameter('journal', $journal->getId());
                }
                return $qb;
            }
                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\JournalSection',
            'user' => null,
            'journal' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_journalsection';
    }

}
