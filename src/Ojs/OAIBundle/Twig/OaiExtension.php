<?php

namespace Ojs\OAIBundle\Twig;

class OaiExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('stripInvalidXml', [$this, 'stripInvalidXmlFilter'])
        ];
    }

    public function stripInvalidXmlFilter($value)
    {
        $result = "";

        if (empty($value)) {
            return $result;
        }

        $length = strlen($value);

        for ($i = 0; $i < $length; $i++) {
            $current = ord($value{$i});

            if (($current == 0x9) ||
                ($current == 0xA) ||
                ($current == 0xD) ||
                (($current >= 0x20) && ($current <= 0xD7FF)) ||
                (($current >= 0xE000) && ($current <= 0xFFFD)) ||
                (($current >= 0x10000) && ($current <= 0x10FFFF))
            ) {
                $result .= chr($current);
            } else {
                $result .= " ";
            }
        }

        return $result;
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
