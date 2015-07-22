<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\Common\Params\ArticleFileParams;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueFileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $languages = $options["languages"];

        $builder
            ->add('translations', 'a2lix_translations_gedmo',[
                'translatable_class' => 'Ojs\JournalBundle\Entity\IssueFile'
            ])
            ->add('type',
                'choice',
                [
                    'choices' => ArticleFileParams::$FILE_TYPES,
                ])
            ->add('version')
            ->add('langCode','choice',[
                'choices'=>$languages
            ])
            ->add('issueId','hidden')
            ->add('file',new FileType(),[
                'data_class'=>'Ojs\JournalBundle\Entity\File'
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\IssueFile',
            'languages'=>[]
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_issuefile';
    }
}
