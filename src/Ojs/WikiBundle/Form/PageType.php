<?php
/**
 * Date: 26.11.14
 * Time: 01:48
 * Devs: [
 *   ]
 */

namespace Ojs\WikiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', [
                'label' => 'Başlık'
            ])
            ->add('content', 'textarea', [
                'label' => 'içerik'
            ])
            ->add('tags', 'text', [
                'label' => 'Etiketler'
            ])
            ;
        switch($options['object_type']){
            case 'journal':
                $builder->add('journal_id', 'hidden', [
                    'data' => $options['object_id']
                ]);
                break;

        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\WikiBundle\Entity\Page',
            'object_id' => null,
            'object_type'=>null
        ));
    }

    public function getName()
    {
        return "page";
    }
}