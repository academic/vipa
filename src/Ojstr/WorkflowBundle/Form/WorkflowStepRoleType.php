<?php

namespace Ojstr\WorkflowBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WorkflowStepRoleType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stepId')
            ->add('roleName')
            ->add('description')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojstr\WorkflowBundle\Entity\WorkflowStepRole'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojstr_workflowbundle_workflowsteprole';
    }
}
