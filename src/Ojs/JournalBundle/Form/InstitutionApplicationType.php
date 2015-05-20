<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\InstitutionTypes;
use Ojs\UserBundle\Entity\Role;
use Okulbilisim\LocationBundle\Helper\FormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ojs\UserBundle\Entity\User;

class InstitutionApplicationType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        /** @var FormHelper $helper */
        $helper = $options['helper'];
        /** @var EntityManager $em */
        $em = $options['em'];
        $institutionTypes = $em->getRepository('OjsJournalBundle:InstitutionTypes')->findAll();
        $choices = [
            'types'=>[],
            'countries'=>[]
        ];
        foreach ($institutionTypes as $choice) {
            /** @var InstitutionTypes $choice*/
            $choices['types'][$choice->getId()]=$choice->getName();
        }
        $countries = $em->getRepository('OkulbilisimLocationBundle:Location')->findBy(['type'=>0]);
        foreach ($countries as $country) {
            $choices['countries'][$country->getId()]=$country->getName();
        }


        $builder
            ->add('name', null, ['label' => 'institution.name'])
            ->add('slug', null, ['label' => 'institution.slug'])
            ->add('type','choice',[
                'label' => 'institution.type',
                'choices'=>$choices['types']
            ])
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
            ->add('logo_image','hidden')
            ->add('header_image','hidden')
            ->add('country', 'choice', [
                'choices' => $choices['countries'],
                'attr' => [
                    'label' => 'institution.country',
                    'class' => 'select2-element  bridged-dropdown',
                    'data-to'=>'#'.$this->getName().'_city'
                ]
            ]);
        $helper->addCityField($builder,'Ojs\JournalBundle\Document\InstitutionApplication',true);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Document\InstitutionApplication',
            'em'=>null,
            'helper' => null
        ,
            'attr'=>[
                'novalidate'=>'novalidate'
,'class'=>'form-validate'
            ]
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_institutionapplication';
    }

}
