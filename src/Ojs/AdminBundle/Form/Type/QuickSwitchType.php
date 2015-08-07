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
            'entity',
            [
                'class' => 'Ojs\JournalBundle\Entity\Journal',
                'label_attr' => array('class' => 'sr-only'),
                'attr' => [
                    'class' => 'select2-element',
                    'placeholder' => 'Type a journal name to switch to its dashboard',
                ],
            ]
        )->add('switch', 'submit');
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'ojs_admin_quickswitch';
    }

}
