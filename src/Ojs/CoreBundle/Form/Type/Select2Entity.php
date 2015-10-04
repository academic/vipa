<?php

namespace Ojs\CoreBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Ojs\CoreBundle\Form\DataTransformer\EntitiesToPropertyTransformer;
use Ojs\CoreBundle\Form\DataTransformer\EntityToPropertyTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Router;

/**
 *
 *
 * Class Select2Entity
 * @package Ojs\CoreBundle\Form\Type
 */
class Select2Entity extends AbstractType
{
    protected $em;
    protected $router;
    protected $pageLimit;
    protected $minimumInputLength;

    public function __construct(EntityManager $em, Router $router, $minimumInputLength, $pageLimit)
    {
        $this->em = $em;
        $this->router = $router;
        $this->minimumInputLength = $minimumInputLength;
        $this->pageLimit = $pageLimit;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = $options['multiple']
            ? new EntitiesToPropertyTransformer($this->em, $options['class'], $options['text_property'])
            : new EntityToPropertyTransformer($this->em, $options['class'], $options['text_property']);

        $builder->addViewTransformer($transformer, true);

    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);
        // make variables available to the view
        $view->vars['remote_path'] = $options['remote_path']
            ?: $this->router->generate($options['remote_route'], $options['remote_params']).
            '?page_limit='.$options['page_limit'];

        $varNames = array('multiple', 'minimum_input_length', 'placeholder');
        foreach ($varNames as $varName) {
            $view->vars[$varName] = $options[$varName];
        }

        if ($options['multiple']) {
            $view->vars['full_name'] .= '[]';
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'class' => null,
                'remote_path' => null,
                'remote_route' => null,
                'remote_params' => array(),
                'multiple' => false,
                'compound' => false,
                'minimum_input_length' => $this->minimumInputLength,
                'page_limit' => $this->pageLimit,
                'text_property' => null,
                'placeholder' => '',
                'required' => false,
            )
        );
    }

    public function getName()
    {
        return 'select2entity';
    }
}
