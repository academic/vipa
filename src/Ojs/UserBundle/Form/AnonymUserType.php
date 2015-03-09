<?php
/**
 * Date: 17.01.15
 * Time: 23:26
 */

namespace Ojs\UserBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnonymUserType extends AbstractType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "anonym_user";
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('first_name')
            ->add('last_name')
            ->add('email')
            ->add('roles', 'entity', array(
                'class' => 'Ojs\UserBundle\Entity\Role',
                'property' => 'name',
                'multiple' => true,
                'expanded' => false,
                'attr' => array('class' => 'select2-element', 'style' => 'width:100%')
            ))
        ;
    }


    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\UserBundle\Entity\User'
        ));
    }
}