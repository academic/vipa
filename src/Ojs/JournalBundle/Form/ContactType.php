<?php

namespace Ojs\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title', 'text', ['label' => 'title'])
                ->add('firstName', 'text', ['label' => 'firstname'])
                ->add('lastName', 'text', ['label' => 'lastname'])
                ->add('address', 'text', ['label' => 'address'])
                ->add('country', 'integer', ['label' => 'country'])
                ->add('city', 'text', ['label' => 'city'])
                ->add('phone', 'text', ['label' => 'phone'])
                ->add('fax', 'text', ['label' => 'fax'])
                ->add('email', 'email', ['label' => 'email'])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\Contact',
            'attr' => [
                'novalidate' => 'novalidate', 'class' => 'form-validate'
            ]
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_contact';
    }

}
