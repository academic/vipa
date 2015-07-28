<?php

namespace Ojs\UserBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Ojs\UserBundle\Form\DataTransformer\UsersToPropertyTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Router;
/**
 *
 *
 * Class UsersFieldType
 * @package Ojs\UserBundle\Form\Type
 */
class UsersFieldType extends AbstractType
{
    protected $em;
    protected $router;
    protected $pageLimit;
    protected $minimumInputLength;
    protected $dataType;
    public function __construct(EntityManager $em, Router $router, $minimumInputLength, $pageLimit)
    {
        $this->em = $em;
        $this->router = $router;
        $this->minimumInputLength = $minimumInputLength;
        $this->pageLimit = $pageLimit;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new UsersToPropertyTransformer($this->em), true);
    }
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);
        // make variables available to the view
        $view->vars['remote_path'] = $this->router->generate($options['remote_route'], $options['remote_params']).'?page_limit='.$options['page_limit'];
        $varNames = array('minimum_input_length', 'placeholder');
        foreach($varNames as $varName) {
            $view->vars[$varName] = $options[$varName];
        }
        $view->vars['full_name'] .= '[]';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'remote_route' => null,
            'remote_params' => array(),
            'compound' => false,
            'minimum_input_length' => $this->minimumInputLength,
            'page_limit' => $this->pageLimit,
            'placeholder' => '',
            'required' => false,
        ));
    }
    public function getName()
    {
        return 'users_type';
    }
}
