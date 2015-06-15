<?php

namespace Ojs\UserBundle\Form\Type;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NotificationType extends AbstractType
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
                'sender',
                'autocomplete',
                [
                    'class' => 'Ojs\UserBundle\Entity\User',
                    'label' => 'Sender User',
                    'attr' => [
                        'class' => 'autocomplete',
                        'data-list' => $this->container->get('router')->generate('api_get_users'),
                        'data-get' => $this->container->get('router')->generate('ojs_api_homepage').'public/user/get/',
                        "placeholder" => "type a username",
                    ],
                ]
            )
            ->add(
                'recipient',
                'autocomplete',
                [
                    'class' => 'Ojs\UserBundle\Entity\User',
                    'label' => 'Recipient User',
                    'attr' => [
                        'class' => 'autocomplete',
                        'data-list' => $this->container->get('router')->generate('api_get_users'),
                        'data-get' => $this->container->get('router')->generate('ojs_api_homepage').'public/user/get/',
                        "placeholder" => "type a username",
                    ],
                ]
            )
            ->add('entityId')
            ->add('entityName')
            ->add('isRead')
            ->add('text')
            ->add('action')
            ->add('level');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\UserBundle\Entity\Notification',
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
        return 'ojs_userbundle_notification';
    }
}
