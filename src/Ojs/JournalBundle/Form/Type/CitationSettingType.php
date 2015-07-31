<?php

namespace Ojs\JournalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CitationSettingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'citation',
                'autocomplete',
                [
                    'class' => 'Ojs\JournalBundle\Entity\Citation',
                    'attr' => [
                        'class' => 'autocomplete',
                        'style' => 'width:100%',
                        'data-list' => $options['citationsEndPoint'],
                        'data-get' => $options['citationEndPoint'],
                        "placeholder" => "type a citation",
                    ],
                ]
            )
            ->add('setting')
            ->add('value');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\CitationSetting',
                'citationsEndPoint' => '/',
                'citationEndPoint' => '/',
                'attr' => [
                    'novalidate' => 'novalidate',
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
        return 'ojs_journalbundle_citationsetting';
    }
}
