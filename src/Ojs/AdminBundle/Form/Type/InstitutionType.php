<?php

namespace Ojs\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstitutionType extends AbstractType
{
    private $selfId;

    /**
     * InstitutionType constructor.
     * @param $selfId
     */
    public function __construct($selfId = null)
    {
        $this->selfId = $selfId;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $selfId = $this->selfId;
        $builder
            ->add(
                'name',
                'text',
                [
                    'label' => 'name',
                    'required' => true,
                    'attr' => [
                        'class' => "validate[required]",
                    ],
                ]
            )
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_institution';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\Institution',
                'cascade_validation' => true,
                'institution' => null,
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'validate-form',
                ],
            )
        );
    }
}
