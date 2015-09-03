<?php

namespace Ojs\CoreBundle\Service\Twig;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Translation\TranslatorInterface;

class DisplayExtension extends \Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * exclude vars for basic entity
     * @var array
     */
    private $excludeVars = ['locale','contentChanged','currentLocale','defaultLocale','publicURI','currentTranslation'];

    /**
     * exclude vars for translation entity
     * @var array
     */
    private $translationExcludeVars = ['locale', 'id', 'translatable'];

    /**
     * expose vars for basic entity
     * @var array
     */
    private $exposeVars = [];

    /**
     * @var array
     */
    private $files = [];

    /**
     * @var
     */
    private $entity;

    /**
     * @var
     */
    private $normalizedEntity;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('display', array($this, 'getDisplay'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function getDisplay($entity, $options = array())
    {
        if(!method_exists($entity, 'display')){
            throw new Exception('Please create an public display method for object');
        }
        $this->entity = $entity;
        $this->setupOptions($options);
        $this->normalizedEntity = $this->normalizeEntity();
        return $this->createView();
    }

    private function setupOptions($options)
    {
        if(isset($options['files'])){
            if(is_array($options['files'])){
                $this->files = $options['files'];
            }else{
                throw new Exception('files option must be an array');
            }
        }
        if(isset($options['exclude'])){
            if(is_array($options['exclude'])){
                $this->excludeVars = array_merge($this->excludeVars, $options['exclude']);
            }elseif(is_string($options['exclude'])){
                $this->excludeVars[] = $options['exclude'];
            }else{
                throw new Exception('exclude option must be array or string');
            }
        }
        if(isset($options['expose'])){
            if(is_array($options['expose'])){
                $this->exposeVars = array_merge($this->exposeVars, $options['expose']);
            }elseif(is_string($options['expose'])){
                $this->exposeVars[] = $options['expose'];
            }else{
                throw new Exception('expose option must be array or string');
            }
            foreach($this->exposeVars as $expose){
                if(in_array($expose, $this->excludeVars)){
                    $this->excludeVars = array_diff($this->excludeVars, $this->exposeVars);
                }
            }
        }
    }

    /**
     * @return mixed
     */
    private function normalizeEntity()
    {
        $this->normalizedEntity = $this->entity->display();
        $this->customNormalize();
        foreach ($this->normalizedEntity as $fieldName => $fieldValue) {
            if (in_array($fieldName, $this->excludeVars)) {
                unset($this->normalizedEntity[$fieldName]);
                continue;
            } elseif (empty($fieldValue)) {
                $this->normalizedEntity[$fieldName] = '-';
                continue;
            }
            if (is_bool($fieldValue)) {
                $this->normalizeBool($fieldName);
            }
            if (is_object($fieldValue)) {
                $this->normalizeObject($fieldName);
            }
        }

        return $this->normalizedEntity;
    }

    private function customNormalize()
    {
        $this->normalizeTranslations();
        $this->normalizeFiles();
        if (method_exists($this->entity, 'getStatusText')) {
            if (!is_array($this->entity->getStatusText())) {
                $this->normalizedEntity['status'] = '<span style="color: '.$this->entity->getStatusColor(
                    ).'">'.$this->translator->trans($this->entity->getStatusText()).'</span>';
            } else {
                $this->normalizedEntity['status'] = $this->translator->trans('status.unknown');
            }
        }

        return;
    }

    private function normalizeTranslations()
    {
        if(!isset($this->normalizedEntity['translations'])){
            return;
        }
        foreach($this->normalizedEntity['translations'] as $translation){
            if(!method_exists($translation, 'display')){
                throw new Exception('Please create an public display method for translation object');
            }
            $translation = $translation->display();
            foreach($translation as $translationFieldName => $translationFieldValue){
                if(!in_array($translationFieldName, $this->translationExcludeVars)
                    && $translationFieldValue != ''){
                    if(isset($this->normalizedEntity[$translationFieldName])
                        && !empty($this->normalizedEntity[$translationFieldName])){
                        $this->normalizedEntity[$translationFieldName].='<br>'.$translationFieldValue.' ['.$translation['locale'].']';
                    }else{
                        $this->normalizedEntity[$translationFieldName] = $translationFieldValue.' ['.$translation['locale'].']';
                    }
                }
            }
        }
        unset($this->normalizedEntity['translations']);
    }

    private function normalizeFiles()
    {
        if(empty($this->files)){
            return;
        }
        foreach($this->files as $fileKey => $file){
            if(!isset($this->normalizedEntity[$fileKey])){
                throw new Exception('This file field not exists!');
            }
            $this->normalizedEntity[$fileKey] = '<a href="uploads/'.$this->files[$fileKey]["dir"].'/'.$this->normalizedEntity[$fileKey].'" target="_blank">'.$this->normalizedEntity[$fileKey].'</a>';
        }
    }

    private function normalizeBool($fieldName)
    {
        if($this->normalizedEntity[$fieldName]){
            $this->normalizedEntity[$fieldName] = '<i class="fa fa-check-circle-o" style="color:green"></i>';
        }else{
            $this->normalizedEntity[$fieldName] = '<i class="fa fa-times" style="color:red"></i>';
        }
    }

    private function normalizeObject($fieldName)
    {
        $fieldValue = $this->normalizedEntity[$fieldName];
        if(method_exists($fieldValue, '__toString')){
            $this->normalizedEntity[$fieldName] = (string)$fieldValue;
        }
        if(method_exists($fieldValue, 'first')){
            foreach($fieldValue as $collectionObject){
                if(method_exists($collectionObject, '__toString')){
                    $objectToString = (string)$collectionObject;
                    if(is_object($this->normalizedEntity[$fieldName])){
                        $this->normalizedEntity[$fieldName] = $objectToString;
                    }else{
                        $this->normalizedEntity[$fieldName].= '<br>'.$objectToString;
                    }
                }
            }
        }
        if($fieldValue instanceof \DateTime){
            $this->normalizedEntity[$fieldName] = $fieldValue->format('Y-m-d H:i:s');
        }
    }

    private function createView()
    {
        $table = '<table class="table"><tbody>';
        foreach($this->normalizedEntity as $fieldName => $fieldValue){
            if (is_string($fieldValue)) {
                $table .= '<tr>';
                $table .= '<th>'.$this->translator->trans($fieldName).'</th>';
                $table .= '<th>'.$fieldValue.'</th>';
                $table .= '</tr>';
            }
        }
        $table .= '</tbody></table>';

        return $table;
    }

    public function getName()
    {
        return 'display_extension';
    }
}