<?php

namespace Vipa\OAIBundle\Twig;

class OaiExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('fixEncoding', [$this, 'fixEncoding'])
        ];
    }

    public function fixEncoding($value)
    {
        return preg_replace("/[[:cntrl:]]+/", "", $value);
    }

    /**
     * Returns the name of the extension.
     * @return string The extension name
     */
    public function getName()
    {
        return 'vipa_oai_extension';
    }
}
