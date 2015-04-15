<?php

namespace Ojs\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BlockLinkType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('block_id','hidden',['data'=>$options['block_id']])
            ->add('text','text',['label'=>'Text'])
            ->add('url','text',['label'=>'URI'])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\SiteBundle\Entity\BlockLink',
            'block_id'=>null,
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
        return 'ojs_sitebundle_blocklink';
    }
}
