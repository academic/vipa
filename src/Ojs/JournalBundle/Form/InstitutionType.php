<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityManager;
use Ojs\JournalBundle\Entity\Institution;
use Okulbilisim\LocationBundle\Entity\City;
use Okulbilisim\LocationBundle\Entity\Country;
use Okulbilisim\LocationBundle\Helper\FormHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstitutionType extends AbstractType {

    /** @var ContainerInterface  */
    private $container ;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var FormHelper $helper */
        $helper = $options['helper'];
        $builder
                ->add('name', 'text', [
                    'label' => 'name',
                    'required' => true,
                    'attr'=>[
                        'class'=>"validate[required]"
                    ]
                ])
                ->add('slug', 'text', [
                    'label' => 'institution.slug',
                    'attr'=>[
                        'class'=>"validate[required]"
                    ]
                ])
                ->add('institution_type', 'entity', [
                    'label' => 'institutiontype',
                    'class' => 'Ojs\JournalBundle\Entity\InstitutionTypes',
                    'attr'=>[
                        'class'=>"validate[required]"
                    ]
                ])
                ->add('parent','autocomplete',[
                    'class' => 'Ojs\JournalBundle\Entity\Institution',
                    'attr' => [
                            'class' => 'autocomplete',
                            'style' => 'width:100%',
                            'data-list' => $this->container->get('router')->generate('ojs_api_homepage') . "public/search/institute",
                            'data-get' => $this->container->get('router')->generate('ojs_api_homepage') . "public/institution/get/",
                            "placeholder" => "type a institution name"
                     ]
                ])
                ->add('about', 'textarea', ['label' => 'about'])
                ->add('address', 'textarea', ['label' => 'address'])
                ->add('addressLat', 'text', ['label' => 'addressLat'])
                ->add('addressLong', 'text', ['label' => 'addressLong'])
                ->add('phone', 'text', ['label' => 'phone'])
                ->add('fax', 'text', ['label' => 'fax'])
                ->add('email', 'email', ['label' => 'email'])
                ->add('url', 'url', ['label' => 'url'])
                ->add('wiki')
                ->add('logo', 'hidden')
                ->add('header', 'hidden')
                ->add('verified', 'checkbox', [
                    'label' => 'verified',
                    'attr'=>[
                        'class'=>"checkbox"
                    ]
                ])
                ->add('country', 'entity', [
                    'label' => 'country',
                    'class' => 'Okulbilisim\LocationBundle\Entity\Country',
                    'attr' => [
                        'class' => 'select2-element  bridged-dropdown',
                        'data-to' => '#' . $this->getName() . '_city'
                    ]
        ]);
        $helper->addCityField($builder, 'Ojs\JournalBundle\Entity\Institution');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\Institution',
            'helper' => null,
            'attr' => [
                'novalidate' => 'novalidate',
                'class' => 'validate-form'
            ],
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_institution';
    }

}
