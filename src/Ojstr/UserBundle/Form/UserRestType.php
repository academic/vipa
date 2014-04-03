<?php

namespace Ojstr\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\MinLength; 

class UserRestType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
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
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ojstr\UserBundle\Entity\User',
            'csrf_protection' => false
        ));
    }

    public function getDefaultOptions(array $options) {
        $collectionConstraint = new Collection(array(
            'username' => new MinLength(5),
            'password' => new MinLength(5),
            'email' => new Email(array('message' => $this->get('translator')->trans('Invalid email address'))),
        ));

        $options['validation_constraint'] = $collectionConstraint;
        return $options;
    }

    /**
     * @return string
     * @description return '' to handle post parameters as they sent
     */
    public function getName() {
        return '';
    }

}
