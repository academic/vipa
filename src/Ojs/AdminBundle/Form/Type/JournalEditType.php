<?php

namespace Ojs\AdminBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalEditType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'publisher',
                null,
                [
                    'label' => 'publisher',
                    'attr' => [
                        'class' => 'select2-element validate[required]',
                    ]
                ]
            )
            ->add(
                'accessModal',
                'choice',
                [
                    'label' => 'journal.access.modal',
                    'choices' => [
                        0 => 'open.access',
                        1 => 'access.with.subscription',
                    ],
                ]
            )
            ->add(
                'mandatoryLang',
                'entity',
                [
                    'label' => 'Mandatory Lang',
                    'class' => 'Ojs\JournalBundle\Entity\Lang',
                    'attr' => [
                        'class' => 'select2-element ',
                    ]
                ]
            )
            ->add(
                'languages',
                'entity',
                array(
                    'label' => 'Supported Languages',
                    'class' => 'Ojs\JournalBundle\Entity\Lang',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => false,
                    'attr' => [
                        'class' => 'select2-element validate[required]',
                    ]
                )
            )
            ->add('path', 'hidden', [
                'label' => 'journal.path',
                'required' => false
                ]
            )
            ->add('domain', 'hidden', [
                'label' => 'journal.domain',
                'required' => false
                ]
            )
            ->add(
                'status',
                'choice',
                [
                    'label' => 'status',
                    'choices' => Journal::$statuses,
                ]
            )
            ->add('slug', 'text', [
                'label' => 'journal.slug',
                'required' => false,
                ]
            )
            ->add('note', 'textarea', [
                    'label' => 'journal.note',
                    'required' => false,
                ]
            )
            ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\Journal',
                'cascade_validation' => true
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_adminbundle_journal_edit_type';
    }
}
