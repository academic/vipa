<?php

namespace Ojs\Common\Twig;

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
     * @param null $extraOptions
     * @return string
     */
    public function getDisplay($entity, $extraOptions = null)
    {
        $preparedEntity = $this->prepareEntity($entity);
        $table = '<table class="table"><tbody>';
        foreach($preparedEntity as $fieldName => $fieldValue){
            if(!is_array($fieldValue)){
                $table.= '<tr>';
                $table.= '<th>'.$fieldName.'</th>';
                $table.= '<th>'.$fieldValue.'</th>';
                $table.= '</tr>';
            }
        }
        $table.='</tbody></table>';
        return $table;
    }

    /**
     * @param $entity
     * @return mixed
     */
    private function prepareEntity($entity)
    {
        $entityToArray = json_decode($this->serializer->serialize($entity, 'json'), true);
        foreach($entityToArray as $fieldName => $fieldValue){
            if(is_array($fieldValue)){
                if($fieldName == 'translations'){
                    foreach($fieldValue as $translationLocale => $translation){
                        foreach($translation as $translationFieldName => $translationFieldValue){
                            if($translationFieldName !== 'locale' && $translationFieldName !== 'id'){
                                if(isset($entityToArray[$translationFieldName])){
                                    $entityToArray[$translationFieldName].='<br>'.$translationFieldValue.' ['.$translationLocale.']';
                                }else{
                                    $entityToArray[$translationFieldName] = $translationFieldValue.' ['.$translationLocale.']';
                                }
                            }
                        }
                    }
                }
            }
        }
        return $entityToArray;
    }

    public function getName()
    {
        return 'display_extension';
    }
}
