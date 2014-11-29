<?php

namespace Ojs\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstitutionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text',['required'=>true])
            ->add('slug','text',[
                'required'=>true,
            ])
            ->add('institution_type','entity',[
                'class'=>'Ojs\JournalBundle\Entity\InstitutionTypes'
            ])
            ->add('about')
            ->add('address')
            ->add('city')
            ->add('country')
            ->add('addressLat')
            ->add('addressLong')
            ->add('phone')
            ->add('fax')
            ->add('email')
            ->add('url')
            ->add('wiki')
            ->add('logo','hidden')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\Institution'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_institution';
    }
}
