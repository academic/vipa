<?php

namespace Ojs\CoreBundle\Entity;

/**
 * Class GenericEntityInterface
 * @package Ojs\CoreBundle\Entity
 */
trait GenericEntityTrait
{
    use BlameableTrait;
    use SoftDeletableTrait;
    use TimestampableTrait;
    use TranslateableTrait;
    use TagsTrait;
    use DisplayTrait;
}
