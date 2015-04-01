<?php

namespace Ojs\JournalBundle\Form\JournalSetup;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Step2 extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('issn')
            ->add('eissn')
            ->add('firstPublishDate', 'datetime', array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'date_format' => 'yyyy-MM-dd',
                'with_seconds' => true,
                'data' => new \DateTime()
            ))
            ->add('footer_text','textarea')
            ->add('period')
            ->add('country', 'entity', [
                'class' => 'Okulbilisim\LocationBundle\Entity\Country',
                'attr' => [
                    'class' => 'select2-element'
                ]
            ])
            ->add('Institution', 'entity', [
                'class' => 'Ojs\JournalBundle\Entity\Institution',
                'attr' => [
                    'class' => 'select2-element'
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\Journal'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_journal';
    }

}
