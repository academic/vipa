<?php

namespace Ojs\JournalBundle\Form\JournalSetup;

use Doctrine\ORM\EntityRepository;
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
            ->add('issn', null,[
                'attr' => [
                    'class' => 'validate[required]'
                ]
            ])
            ->add('eissn', null)
            ->add('firstPublishDate', 'collot_datetime', array(
                'date_format' => 'yyyy-MM-dd',
            ))
            ->add('footer_text','textarea')
            ->add('period')
            ->add('country', 'entity', [
                'class' => 'Okulbilisim\LocationBundle\Entity\Location',
                'attr' => [
                    'class' => 'select2-element'
                ],
                'query_builder'=>function(EntityRepository $em){
                    return $em->createQueryBuilder('c')
                        ->where("c.type","0");
                }
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
            'data_class' => 'Ojs\JournalBundle\Entity\Journal',
            'attr'=>[
                'novalidate'=>'novalidate'
,'class'=>'form-validate'
            ]
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
