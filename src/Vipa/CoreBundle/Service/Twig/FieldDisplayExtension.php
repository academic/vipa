<?php

namespace Vipa\CoreBundle\Service\Twig;

use Symfony\Component\Translation\TranslatorInterface;
use Doctrine\Common\Annotations\Reader;
use Liip\ImagineBundle\Templating\ImagineExtension;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class FieldDisplayExtension extends \Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ImagineExtension
     */
    private $imagine;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var Router
     */
    private $router;

    public function __construct(TranslatorInterface $translator, Reader $reader , ImagineExtension $imagine, Router $router)
    {
        $this->translator = $translator;
        $this->reader = $reader;
        $this->imagine = $imagine;
        $this->router = $router;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('fieldDisplay', array($this, 'getFieldDisplay'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @param $displayEntity
     * @param $field
     * @param bool|false $arrayReturn
     * @param bool|false $isKeywords
     * @return array|string
     */
    public function getFieldDisplay($displayEntity, $field, $arrayReturn = false, $isKeywords = false)
    {
        $createGetterFunction = 'get'.ucfirst($field);
        $fieldTranslations = [];
        foreach($displayEntity->getTranslations() as $langCode => $translation){
            if(!empty($translation->$createGetterFunction()) && $translation->$createGetterFunction() != '-'){
                $fieldTranslations[$langCode] = $translation->$createGetterFunction();
            }
        }
        if(count($fieldTranslations)<2 && count($fieldTranslations) !== 0){
            return array_values($fieldTranslations)[0];
        }
        if($arrayReturn){
            return $fieldTranslations;
        }else{
            return $this->generateTabsView($fieldTranslations, $field, $isKeywords);
        }
    }

    private function generateTabsView($fieldTranslations ,$field, $isKeywords = false)
    {
        $template = '<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">';
        $i = 0;
        foreach($fieldTranslations as $langCode => $translationValue){
            if($i == 0){
                $template.= '<li class="active"><a href="#'.$field.'-'.$langCode.'" data-toggle="tab">'.$langCode.'</a></li>';
            }else{
                $template.= '<li><a href="#'.$field.'-'.$langCode.'" data-toggle="tab">'.$langCode.'</a></li>';
            }
            $i++;
        }
        $template.= '</ul><div id="my-tab-content" class="tab-content">';

        $t = 0;
        foreach($fieldTranslations as $langCode => $translationValue){
            $activeString = $t == 0? 'active': '';
            $template.= '<div class="tab-pane '.$activeString.'" id="'.$field.'-'.$langCode.'">';
            if($isKeywords){
                $explodeValue = explode(',', $translationValue);
                $i = 0;
                foreach($explodeValue as $explodeItem){
                    $commaSign = $i != 0 ? ',': '';
                    $template.= $commaSign.'<a href="'.$this->router->generate('vipa_search_index', ['q'=> $explodeItem]).'" target="_blank">'.$explodeItem.'</a>';
                    $i++;
                }
            }else{
                $template.= $translationValue;
            }
            $template.='</div>';
            $t++;
        }
        $template.='</div>';
        return $template;
    }

    public function getName()
    {
        return 'field_display_extension';
    }
}
