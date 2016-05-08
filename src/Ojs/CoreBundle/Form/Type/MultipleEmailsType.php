<?php

namespace Ojs\CoreBundle\Form\Type;

use Ojs\CoreBundle\Form\DataTransformer\MultipleEmailsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MultipleEmailsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->addViewTransformer(new MultipleEmailsTransformer(), true);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_multiple_emails';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'textarea';
    }
}
