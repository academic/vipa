<?php

namespace Ojs\Common\Twig;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Translation\TranslatorInterface;
use JMS\Serializer\SerializerInterface;

class DisplayExtension extends \Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(TranslatorInterface $translator, $serializer)
    {
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('display', array($this, 'getDisplay'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @param $entity
     * @param array $extraOptions
     * @return string
     */
    public function getDisplay($entity, $extraOptions = array())
    {
        $preparedEntity = $this->prepareEntity($entity, $extraOptions);
        $table = '<table class="table"><tbody>';
        foreach($preparedEntity as $fieldName => $fieldValue){
            if(!is_array($fieldValue) && !is_object($fieldValue)){
                $table.= '<tr>';
                $table.= '<th>'.$this->translator->trans($fieldName).'</th>';
                $table.= '<th>'.$fieldValue.'</th>';
                $table.= '</tr>';
            }
        }
        $table.='</tbody></table>';
        return $table;
    }

    /**
     * @param $entity
     * @param array $options
     * @return mixed
     */
    private function prepareEntity($entity, $options = array())
    {
        if(!method_exists($entity, 'display')){
            throw new Exception('Please create an public display method for object');
        }
        $files = [];
        if(isset($options['files'])){
            $files = $options['files'];
        }
        $entityToArray = $entity->display();
        foreach($entityToArray as $fieldName => $fieldValue){
            if(method_exists($entity, 'getStatusText')){
                if(!is_array($entity->getStatusText())){
                    $entityToArray['status'] = '<span style="color: '.$entity->getStatusColor().'">'.$this->translator->trans($entity->getStatusText()).'</span>';
                }else{
                    $entityToArray['status'] = $this->translator->trans('status.unknown');
                }
            }
            foreach($files as $fileKey => $file){
                if($fileKey == $fieldName){
                    $entityToArray[$fieldName] = '<a href="uploads/'.$files[$fieldName]["dir"].'/'.$fieldValue.'" target="_blank">'.$fieldValue.'</a>';
                }
            }
            if(in_array($fieldName, $this->excludeVars())){
                unset($entityToArray[$fieldName]);
            }elseif(empty($fieldValue)){
                $entityToArray[$fieldName] = '-';
            }
            if(is_bool($fieldValue)){
                if($fieldValue){
                    $entityToArray[$fieldName] = '<i class="fa fa-check-circle-o" style="color:green"></i>';
                }else{
                    $entityToArray[$fieldName] = '<i class="fa fa-times" style="color:red"></i>';
                }
            }
            if(is_object($fieldValue)){
                if(method_exists($fieldValue, '__toString')){
                    $entityToArray[$fieldName] = (string)$fieldValue;
                }
                if(method_exists($fieldValue, 'first')){
                    foreach($fieldValue as $collectionObject){
                        if(method_exists($collectionObject, '__toString')){
                            $objectToString = (string)$collectionObject;
                            if(is_object($entityToArray[$fieldName])){
                                $entityToArray[$fieldName] = $objectToString;
                            }else{
                                $entityToArray[$fieldName].= '<br>'.$objectToString;
                            }
                        }
                    }
                }
                if($fieldValue instanceof \DateTime){
                    $entityToArray[$fieldName] = $fieldValue->format('Y-m-d H:i:s');
                }
            }
            if($fieldName == 'translations'){
                foreach($fieldValue as $translation){
                    $translation = $translation->display();
                    foreach($translation as $translationFieldName => $translationFieldValue){
                        if(!in_array($translationFieldName, $this->translationExcludeVars())
                            && $translationFieldValue != ''){
                            if(isset($entityToArray[$translationFieldName]) && $entityToArray[$translationFieldName] != '-'){
                                $entityToArray[$translationFieldName].='<br>'.$translationFieldValue.' ['.$translation['locale'].']';
                            }else{
                                $entityToArray[$translationFieldName] = $translationFieldValue.' ['.$translation['locale'].']';
                            }
                        }
                    }
                }
            }
        }
        return $entityToArray;
    }

    /**
     * exclude vars for basic entity
     * @return array
     */
    private function excludeVars()
    {
        return ['locale', 'contentChanged', 'currentLocale', 'defaultLocale', 'publicURI', 'currentTranslation'];
    }

    /**
     * exclude vars for translation entity
     * @return array
     */
    private function translationExcludeVars()
    {
        return ['locale', 'id', 'translatable'];
    }

    public function getName()
    {
        return 'display_extension';
    }
}
