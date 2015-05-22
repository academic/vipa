<?php

namespace Ojs\Common\Entity;

/**
 * Class GenericEntityInterface
 * @package Ojs\Common\Entity
 */
trait GenericEntityTrait
{
    use BlameableTrait;
    use SoftDeletableTrait;
    use TimestampableTrait;
    use TranslateableTrait;
    use TagsTrait;
}
