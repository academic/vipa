<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityRepository;
use Okulbilisim\LocationBundle\Helper\FormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstitutionApplicationType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        /** @var FormHelper $helper */
        $helper = $options['helper'];

        $builder
            ->add('name', null, ['label' => 'institution.name'])
            ->add('slug', null, ['label' => 'institution.slug'])
            ->add(
                'type',
                'entity',
                array(
                    'class' => 'OjsJournalBundle:InstitutionTypes',
                    'label' => 'institution.type',
                )
            )
            ->add('about', null, ['label' => 'institution.about'])
            ->add('address', null, ['label' => 'institution.address'])
            ->add('lat', null, ['label' => 'institution.lat'])
            ->add('lon', null, ['label' => 'institution.lon'])
            ->add('email', null, ['label' => 'institution.email'])
            ->add('fax', null, ['label' => 'institution.fax'])
            ->add('phone', null, ['label' => 'institution.phone'])
            ->add('url', null, ['label' => 'institution.url'])
            ->add('wiki_url', null, ['label' => 'institution.wiki_url'])
            ->add('tags', null, ['label' => 'institution.tags'])
            ->add('logo_image', 'hidden')
            ->add('header_image', 'hidden')
            ->add(
                'country',
                'entity',
                array(
                    'class' => 'OkulbilisimLocationBundle:Location',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('l')->andWhere('l.type = 0');
                    },
                    'label' => 'institution.country',
                    'attr' => [
                        'class' => 'select2-element  bridged-dropdown',
                        'data-to' => '#'.$this->getName().'_city',
                    ],
                )
            );
        $helper->addCityField($builder, 'Ojs\JournalBundle\Document\InstitutionApplication', true);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_institutionapplication';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Document\InstitutionApplication',
                'helper' => null,
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
