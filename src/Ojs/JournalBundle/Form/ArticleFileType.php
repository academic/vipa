<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ojs\Common\Params\ArticleFileParams;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleFileType extends AbstractType
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('keywords')
            ->add('description')
            ->add('type','choice',[
                'choices'=>ArticleFileParams::$FILE_TYPES
            ])
            ->add('article','autocomplete',[
                'class' => 'Ojs\JournalBundle\Entity\Article',
                'attr' => [
                    'class' => 'autocomplete',
                    'style' => 'width:100%',
                    'data-list' => $this->container->get('router')->generate('ojs_api_homepage') . "public/search/article",
                    'data-get' => $this->container->get('router')->generate('ojs_api_homepage') . "public/article/get/",
                    "placeholder" => "type a journal name"
                ],
            ])
            ->add('version')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\ArticleFile',
            'user'=> null ,
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
        return 'ojs_journalbundle_articlefile';
    }
}
