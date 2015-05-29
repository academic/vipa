<?php

namespace Ojs\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ContactType extends AbstractType
{
    /** @var  ContainerInterface */
    private $container;

    public function __construct($container)
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
                ->add('title', 'text', ['label' => 'title'])
                ->add('firstName', 'text', ['label' => 'firstname'])
                ->add('lastName', 'text', ['label' => 'lastname'])
                ->add('address', 'text', ['label' => 'address'])
                ->add('country', 'entity', [
                    'label' => 'country',
                    'class' => 'Okulbilisim\LocationBundle\Entity\Location',
                    'attr' => [
                        'class' => "select2-element validate[required]",
                    ],
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                            ->where('t.parent_id = 0');
                    }
                ])
                ->add('city', 'autocomplete', [
                    'class' => 'Okulbilisim\LocationBundle\Entity\Location',
                    'label' => 'city',
                    'attr' => [
                        'class' => 'autocomplete',
                        'data-list' => $this->container->get('router')->generate('ojs_api_homepage')."public/search/location",
                        'data-get' => $this->container->get('router')->generate('ojs_api_homepage')."public/location/get/",
                        "placeholder" => "type a journal name",
                    ],
                ])
                ->add('phone', 'text', ['label' => 'phone'])
                ->add('fax', 'text', ['label' => 'fax'])
                ->add('email', 'email', ['label' => 'email'])
                ->add('tags', 'text', array(
                        'label' => 'keywords',
                        'attr' => [
                            'class' => ' form-control input-xxl',
                            'data-role' =>  'tagsinput',
                            'placeholder' => 'Comma-seperated tag list'
                        ]
                    )
                )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\Contact',
            'attr' => [
                'novalidate' => 'novalidate', 'class' => 'form-validate',
            ],
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_contact';
    }
}
