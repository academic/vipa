<?php

namespace Vipa\JournalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class JournalUserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('user');
    }

    /**
     * Returns the parent of this type.
     * @return string The parent of this type
     */
    public function getParent()
    {
        return 'vipa_journalbundle_journaluser';
    }
}