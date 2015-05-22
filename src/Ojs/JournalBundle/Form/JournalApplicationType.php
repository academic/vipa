<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityManager;
use Ojs\JournalBundle\Entity\Lang;
use Ojs\JournalBundle\Entity\Subject;
use Ojs\JournalBundle\Entity\Institution;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JournalApplicationType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var EntityManager $em */
        $em = $options['em'];
        $institution = $em->getRepository('OjsJournalBundle:Institution')->findAll();
        $languages = $em->getRepository('OjsJournalBundle:Lang')->findAll();
        $subjects = $em->getRepository('OjsJournalBundle:Subject')->findAll();
        $countries = $em->getRepository('OkulbilisimLocationBundle:Location')->findBy(['type' => 0]);
        $choices = [
            'subjects' => [],
            'institutions' => [],
            'languages' => [],
            'countries' => [],
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
            ->add('title', null, ['label' => 'journal.title'])
            ->add('titleAbbr', null, ['label' => 'journal.titleAbbr'])
            ->add('titleTransliterated', null, ['label' => 'journal.titleTransliterated'])
            ->add('subtitle', null, ['label' => 'journal.subtitle'])
            ->add('domain', null, ['label' => 'journal.domain'])
            ->add('country', 'choice', [
                'choices' => $choices['countries'],
                'attr' => ['class' => 'select2-element'],
                'label' => 'journal.country', ])
            ->add('issn', null, ['label' => 'journal.issn'])
            ->add('eissn', null, ['label' => 'journal.eissn'])
            ->add('firstPublishDate', 'collot_datetime', [
                'label' => 'journal.firstPublishDate',
                'date_format' => 'dd-MM-yyyy',
                'pickerOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'startView' => 'month',
                    'minView' => 'month',
                    'todayBtn' => 'true',
                    'todayHighlight' => 'true',
                    'autoclose' => 'true', ], ])
            ->add('period', null, ['label' => 'journal.period'])
            ->add('tags', null, ['attr' => ['class' => 'tags form-control', 'label' => 'journal.tags']])
            ->add('url', null, ['label' => 'journal.url'])
            ->add('institution', 'choice', [
                'choices' => $choices['institutions'],
                'attr' => ['class' => 'select2-element'],
                'label' => 'journal.institution', ])
            ->add('languages', 'choice', [
                'choices' => $choices['languages'],
                'multiple' => true,
                'attr' => ['class' => 'select2-element'],
                'label' => 'journal.languages', ])
            ->add('subjects', 'choice', [
                'choices' => $choices['subjects'],
                'multiple' => true,
                'attr' => ['class' => 'select2-element'],
                'label' => 'journal.subjects', ])
            ->add('coverimage', 'hidden')
            ->add('headerimage', 'hidden')
            ->add('editorName', null, ['label' => 'journal.editor_name'])
            ->add('editorPhone', null, ['label' => 'journal.editor_phone'])
            ->add('editorEmail', null, ['label' => 'journal.editor_email'])
            ->add('assistantEditorName', null, ['label' => 'journal.assistant_editor_name'])
            ->add('assistantEditorPhone', null, ['label' => 'journal.assistant_editor_phone'])
            ->add('assistantEditorEmail', null, ['label' => 'journal.assistant_editor_email'])
            ->add('techContactName', null, ['label' => 'journal.tech_contact_name'])
            ->add('techContactPhone', null, ['label' => 'journal.tech_contact_phone'])
            ->add('techContactEmail', null, ['label' => 'journal.tech_contact_email']);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Document\JournalApplication',
            'em' => null,
            'attr' => [
                'novalidate' => 'novalidate',
                'class' => 'form-validate',
            ],
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_journalapplication';
    }
}
