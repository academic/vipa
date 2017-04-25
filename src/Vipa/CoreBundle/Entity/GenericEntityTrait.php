<?php

namespace Vipa\CoreBundle\Entity;

/**
 * Class GenericEntityInterface
 * @package Vipa\CoreBundle\Entity
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
