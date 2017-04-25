<?php

namespace Vipa\CoreBundle\Entity;

/**
 * AdminJournalTheme
 */
interface ThemeInterface
{
    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getCss();

    /**
     * @param string $css
     */
    public function setCss($css);

    /**
     * @return boolean
     */
    public function isPublic();

    /**
     * @param boolean $public
     */
    public function setPublic($public);
}
