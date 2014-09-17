<?php

namespace Ojstr\UserBundle\Entity\Model;

class Mail
{
    /**
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $body;

    /**
     * @var string
     */
    public $template;

    /**
     * @var array
     */
    public $templateData;
    /**
     * @var string
     */
    public $to;

    /**
     * @var string
     */
    public $from;
}
