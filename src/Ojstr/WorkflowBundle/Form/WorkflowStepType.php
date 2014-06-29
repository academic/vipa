<?php

namespace Ojstr\WorkflowBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WorkflowStepType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('journalId')
            ->add('name')
            ->add('description')
            ->add('nextId')
            ->add('prevId')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojstr\WorkflowBundle\Entity\WorkflowStep'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojstr_workflowbundle_workflowstep';
    }
}
