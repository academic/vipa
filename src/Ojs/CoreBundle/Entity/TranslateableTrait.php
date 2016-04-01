<?php

namespace Ojs\CoreBundle\Entity;

use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\TranslationInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

trait TranslateableTrait
{
    /**
     * @var
     */
    protected $currentTranslation;

    /**
     * @Prezent\CurrentLocale
     * @GRID\Column(title="currentLocale")
     */
    protected $currentLocale;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @deprecated
     * @var string
     */
    protected $locale;

    /**
     * @deprecated
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @deprecated
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    /**
     * @param mixed $currentLocale
     *
     * @return $this
     */
    public function setCurrentLocale($currentLocale)
    {
        $this->currentLocale = $currentLocale;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * @param mixed $defaultLocale
     */
    public function setDefaultLocale($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * Returns translation of given locale
     * @param null $locale
     * @return $this
     */
    public function getTranslationByLocale($locale = null)
    {
        if(null === $locale){
            throw new \RuntimeException('please support an locale');
        }
        foreach($this->translations as $translation){
            $translations[$translation->getLocale()] = $translation;
        }
        return $translations[$locale];
    }

    /**
     * @return TranslationInterface
     */
    public function getCurrentTranslation()
    {
        return $this->currentTranslation;
    }

    /**
     * @param TranslationInterface $currentTranslation
     * @return $this
     */
    public function setCurrentTranslation(TranslationInterface $currentTranslation)
    {
        $this->currentTranslation = $currentTranslation;

        return $this;
    }

    /**
     * @param $field
     * @param bool $withLocale
     * @return string
     */
    public function getLogicalFieldTranslation($field, $withLocale = true)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $fieldValue = $accessor->getValue($this->translate(), $field);
        if(!empty($fieldValue) && $fieldValue !== '-'){
            if($withLocale){
                return '['.$this->getCurrentLocale().']'.$fieldValue;
            }else{
                return $fieldValue;
            }
        }
        foreach($this->translations as $translation){
            $fieldValue = $accessor->getValue($translation, $field);
            if(!empty($fieldValue) && $fieldValue !== '-'){
                if($withLocale){
                    return '['.$translation->getLocale().']'.$fieldValue;
                }else{
                    return $fieldValue;
                }
            }
        }
        return '';
    }
}
