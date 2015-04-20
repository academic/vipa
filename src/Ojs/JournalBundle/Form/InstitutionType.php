<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityManager;
use Ojs\JournalBundle\Entity\Institution;
use Okulbilisim\LocationBundle\Entity\City;
use Okulbilisim\LocationBundle\Entity\Country;
use Okulbilisim\LocationBundle\Helper\FormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstitutionType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var FormHelper $helper */
        $helper = $options['helper'];
        $builder
                ->add('name', 'text', ['label' => 'name', 'required' => true])
                ->add('slug', 'text', [
                    'label' => 'institution.slug',
                    'required' => true,
                ])
                ->add('institution_type', 'entity', [
                    'label' => 'institutiontype',
                    'class' => 'Ojs\JournalBundle\Entity\InstitutionTypes'
                ])
                ->add('parent')
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
                ->add('verified', 'checkbox', ['label' => 'verified'])
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
                'novalidate' => 'novalidate'
                , 'class' => 'form-validate'
            ]
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
