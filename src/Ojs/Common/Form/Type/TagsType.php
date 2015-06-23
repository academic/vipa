<?php

namespace Ojs\Common\Form\Type;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'label' => 'tags',
                'attr' => [
                    'class' => ' form-control input-xxl',
                    'data-role' => 'tagsinputautocomplete',
                    'placeholder' => $this->translator->trans('Comma-seperated tag list'),
                    'data-list' => $this->router->generate('api_get_tags'),
                ],
            )
        );
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'tags';
    }
}
