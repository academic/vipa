<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityManager;
use Ojs\JournalBundle\Entity\Lang;
use Ojs\JournalBundle\Entity\Subject;
use Ojs\JournalBundle\Entity\Institution;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JournalSuggestionType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var EntityManager $em */
        $em = $options['em'];
        $institution = $em->getRepository('OjsJournalBundle:Institution')->findAll();
        $languages = $em->getRepository('OjsJournalBundle:Lang')->findAll();
        $subjects = $em->getRepository('OjsJournalBundle:Subject')->findAll();
        $countries = $em->getRepository('OkulbilisimLocationBundle:Country')->findAll();
        $choices = [
            'subjects' => [],
            'institutions' => [],
            'languages' => [],
            'countries' => []
        ];
        foreach ($institution as $ins) {
            /** @var Institution $ins */
            $choices['institutions'][$ins->getId()] = $ins->getName();
        }

        foreach ($languages as $lang) {
            /** @var Lang $lang */
            $choices['languages'][$lang->getId()] = $lang->getName();
        }

        foreach ($subjects as $subject) {
            /** @var Subject $subject */
            $choices['subjects'][$subject->getId()] = $subject->getSubject();
        }
        foreach ($countries as $country) {
            $choices['countries'][$country->getId()] = $country->getName();
        }

        $builder
            ->add('title')
            ->add('titleAbbr')
            ->add('titleTransliterated')
            ->add('subtitle')
            ->add('domain')
            ->add('subdomain')
            ->add('country','choice',['choices'=>$choices['countries'], 'attr' => ['class' => 'select2']])
            ->add('issn')
            ->add('eissn')
            ->add('firstPublishDate')
            ->add('mission')
            ->add('period')
            ->add('scope')
            ->add('tags',null,['attr'=>['class'=>'tags form-control']])
            ->add('url')
            ->add('institution', 'choice', ['choices' => $choices['institutions'], 'attr' => ['class' => 'select2']])
            ->add('languages', 'choice', ['choices' => $choices['languages'], 'multiple' => true, 'attr' => ['class' => 'select2']])
            ->add('subjects', 'choice', ['choices' => $choices['subjects'], 'multiple' => true, 'attr' => ['class' => 'select2']])
            ->add('coverimage', 'hidden')
            ->add('headerimage', 'hidden');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Document\JournalSuggestion',
            'em' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_journalsuggestion';
    }

}
