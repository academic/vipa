<?php

namespace Vipa\CoreBundle\Form\Type;

use Vipa\CoreBundle\Form\DataTransformer\TagsTransformer;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class TagsType extends AbstractType
{
    /** @var Router */
    private $router;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * @param Router              $router
     * @param TranslatorInterface $translator
     */
    public function __construct(Router $router, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new TagsTransformer(), true);
    }
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);

        $view->vars['remote_path'] = $this->router->generate($options['remote_route'], $options['remote_params']);
        $varNames = array('minimum_input_length', 'placeholder');
        foreach($varNames as $varName) {
            $view->vars[$varName] = $options[$varName];
        }
        $view->vars['full_name'] .= '[]';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => 'tags',
            'remote_route' => 'vipa_core_tag_search',
            'remote_params' => array(),
            'compound' => false,
            'minimum_input_length' => 2,
            'placeholder' => $this->translator->trans('comma.seperated.tag.list'),
            'required' => false,
            'attr' => [
                'class' => ' form-control input-xxl',
            ],
        ));
    }

    public function getName()
    {
        return 'tags';
    }
}
