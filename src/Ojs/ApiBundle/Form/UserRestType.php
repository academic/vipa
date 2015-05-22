<?php

namespace Ojs\ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserRestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('username')
                ->add('password')
                ->add('email')
                ->add('isActive')
                ->add('status');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\UserBundle\Entity\User',
            'csrf_protection' => false
        ));
    }

    public function getDefaultOptions(array $options)
    {
        $collectionConstraint = new Assert\Collection(array(
            'username' => new Assert\Length(array('min'  => 5)),
            'password' => new Assert\Length(array('min'  => 5)),
            'email' => new Assert\Email(array('message' => $this->get('translator')->trans('Invalid email address'))),
        ));

        $options['validation_constraint'] = $collectionConstraint;
        $options['attr'] = [
                'novalidate'=>'novalidate'
        ];
        return $options;
    }

    /**
     * @return string
     * @description return '' to handle post parameters as they sent
     */
    public function getName()
    {
        return '';
    }

}
