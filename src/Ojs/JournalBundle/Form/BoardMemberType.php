<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BoardMemberType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $board = $options['board'];
        $builder
                ->add('board', 'entity', array(
                    'attr' => array('class' => ' form-control select2-element'),
                    'label' => 'board',
                    'class' => 'Ojs\JournalBundle\Entity\Board',
                    'query_builder' =>
                    function (EntityRepository $er) use ($board) {
                /** @var User $user $qb */
                $qb = $er->createQueryBuilder('b');
                if ($board) {
                    $qb->where(
                            $qb->expr()->eq('b.id', ':board')
                    )->setParameter('board', $board->getId());
                }
                return $qb;
            }
                        )
                )
                ->add('user', 'entity', [
                    'label' => 'user',
                    'class' => 'Ojs\UserBundle\Entity\User'
                ])
                ->add('seq', 'number', ['label' => 'order'])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'user' => null,
            'board' => null,
            'data_class' => 'Ojs\JournalBundle\Entity\BoardMember'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_board_member';
    }

}
