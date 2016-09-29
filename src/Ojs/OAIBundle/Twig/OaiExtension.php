<?php

namespace Ojs\OAIBundle\Twig;

use ForceUTF8\Encoding;

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
        return Encoding::toUTF8($value);
    }

    /**
     * Returns the name of the extension.
     * @return string The extension name
     */
    public function getName()
    {
        return 'ojs_oai_extension';
    }
}
