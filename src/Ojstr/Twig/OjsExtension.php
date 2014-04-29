<?php

namespace Ojstr\Twig;

class OjsExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('issn', array($this, 'issnValidateFilter')),
        );
    }

    /**
     * @todo reformat and validate given issn and output with/without errors
     * @param string $issn
     * @return string
     */
    public function issnValidateFilter($issn, $withErrors = FALSE) {
        return $issn."---";
    }

    public function getName() {
        return 'ojs_extension';
    }

}
