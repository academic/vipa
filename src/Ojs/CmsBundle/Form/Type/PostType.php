<?php

namespace Ojs\CmsBundle\Form\Type;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{
    private $container;

    public function __construct(ContainerInterface $servicecontainer)
    {
        $this->container = $servicecontainer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $post_types = $this->container->getParameter('post_types');
        $languages  = $this->container->getParameter('languages');
        $langs = [];
        foreach ($languages as $key => $lang) {
            $langs[$lang['code']]=$lang['name'];
        }

        if (!$post_types)
            $post_types = [];
        $builder
            ->add('title')
            ->add('content', 'ace_editor', array(
                    'label' => 'content',
                    'wrapper_attr' => array(),
                    'width' => 700,
                    'height' => 200,
                    'font_size' => 12,
                    'mode' => 'ace/mode/html',
                    'theme' => 'ace/theme/chrome',
                    'tab_size' => null,
                    'read_only' => null,
                    'use_soft_tabs' => null,
                    'use_wrap_mode' => null,
                    'show_print_margin' => null,
                    'highlight_active_line' => null
                )
            )
            ->add('status', 'choice', [
                'choices' => [
                    0 => 'Disable',
                    1 => 'Enable',
                    2 => 'Canceled'
                ]
            ])
            ;
        if(isset($options['post_type'])){
            $builder->add('post_type','choice',[
                'choices' => $post_types,
                'data' => $options['post_type']
            ]);
        }else{
            $builder->add('post_type','choice',[
                'choices' => $post_types,
            ]);
        }

        $builder->add('locale','choice',[
            'choices'=>$langs
        ]);

        if (isset($options['object']) && $options['object']) {
            $builder
                ->add('object', 'hidden', [
                    'attr' => [
                        'value' => $options['object']
                    ]
                ])
                ->add('objectId', 'hidden', [
                    'attr' => [
                        'value' => $options['objectId']
                    ]
                ]);

        };
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\CmsBundle\Entity\Post',
            'object' => null,
            'objectId' => null,
            'post_type' => 'default'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'okulbilisim_cmsbundle_post';
    }
}
