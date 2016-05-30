<?php

namespace Ojs\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class QuickSwitchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'journal',
            'tetranz_select2entity',
            [
                'remote_route' => 'ojs_admin_journal_autocomplete',
                'class' => 'Ojs\JournalBundle\Entity\Journal',
                'label' => 'journal.switch',
                'label_attr' => array('class' => 'sr-only'),
                'attr' => [
                    'class' => 'select2-element',
                    'placeholder' => 'journal.switch',
                ]
            ]

        )->add('switch', 'submit', ['label' => 'switch']);
    }
}
