<?php

namespace Ojs\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class InstitutionDesignType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'institution',
                'entity',
                array(
                    'label' => 'institution',
                    'class' => 'Ojs\JournalBundle\Entity\Institution',
                    'property' => 'name',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'error_bubbling'=>true,
                )
            )
            ->add('title', 'text', [
                    'label' => 'Title'
                ]
            )
            ->add('editableContent', 'hidden')
            ->add('isPublic', 'checkbox', [
                    'label' => 'ojs.is_public'
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\InstitutionDesign',
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_adminbundle_institution_design';
    }
}
