<?php

namespace Ojs\AdminBundle\Form\Type;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProxyType extends AbstractType
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'proxyUser',
                'autocomplete',
                [
                    'class' => 'Ojs\UserBundle\Entity\User',
                    'label' => 'Proxy User',
                    'attr' => [
                        'class' => 'autocomplete',
                        'data-list' => $this->container->get('router')->generate('api_get_users'),
                        'data-get' => $this->container->get('router')->generate('ojs_api_homepage').'public/user/get/',
                        "placeholder" => "type a username",
                    ],
                ]
            )
            ->add(
                'clientUser',
                'autocomplete',
                [
                    'class' => 'Ojs\UserBundle\Entity\User',
                    'label' => 'Client User',
                    'attr' => [
                        'class' => 'autocomplete',
                        'data-list' => $this->container->get('router')->generate('api_get_users'),
                        'data-get' => $this->container->get('router')->generate('ojs_api_homepage').'public/user/get/',
                        "placeholder" => "type a username",
                    ],
                ]
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\UserBundle\Entity\Proxy',
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'form-validate',
                ],
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_userbundle_proxy';
    }
}
