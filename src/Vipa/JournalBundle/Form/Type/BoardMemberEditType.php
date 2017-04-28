<?php

namespace Vipa\JournalBundle\Form\Type;

use Vipa\JournalBundle\Entity\BoardMember;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoardMemberEditType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seq', null, ['label' => 'board.order'])
            ->add('showMail', 'checkbox', [
                'label' => 'show.mail',
                'required' => false,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => BoardMember::class,
                'cascade_validation' => true,
            )
        );
    }
}
