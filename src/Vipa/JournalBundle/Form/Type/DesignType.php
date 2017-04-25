<?php
namespace Vipa\JournalBundle\Form\Type;

use Vipa\JournalBundle\Entity\Design;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DesignType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                'text',
                [
                    'label' => 'Title'
                ]
            )
            ->add('editableContent', 'hidden')
            ->add(
                'public',
                'checkbox',
                [
                    'required' => false,
                    'label' => 'vipa.is_public'
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Design::class,
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ]
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vipa_journalbundle_design';
    }
}
