<?php

namespace Ojs\JournalBundle\Form\Type;

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
        return 'ojs_journalbundle_journaluser';
    }
}