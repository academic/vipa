<?php

namespace Ojs\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CitationSettingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('citation','autocomplete',[
                'class' => 'Ojs\JournalBundle\Entity\Citation',
                'attr' => [
                    'class' => 'autocomplete',
                    'style' => 'width:100%',
                    'data-list' => '/api/public/search/citation',
                    'data-get' => "/api/public/citation/get/",
                    "placeholder" => "type a citation",
                ],
            ])
            ->add('setting')
            ->add('value')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\CitationSetting',
            'attr' => [
                'novalidate' => 'novalidate', 'class' => 'form-validate',
            ],
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_citationsetting';
    }
}
