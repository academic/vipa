<?php

namespace Ojs\JournalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoardMemberType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'user',
                'tetranz_select2entity',
                [
                    'required' => true,
                    'label' => 'user',
                    'class' => 'Ojs\UserBundle\Entity\User',
                    'remote_route' => 'ojs_journal_user_search'
                ]
            )
            ->add('seq', null, ['label' => 'board.order'])
            ->add('showMail', 'checkbox', [
                'label' => 'show.mail',
                'required' => false,
            ])
            ->add('add', 'submit', ['label' => 'board.addUser'])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\BoardMember',
                'cascade_validation' => true,
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_board';
    }
}
