<?php

namespace Ojs\Common\Entity;

use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * Some common properties and functions for JournalBundle Entities
 *
 * @ExclusionPolicy("all")
 */
class GenericExtendedEntity implements Translatable
{
    use GenericEntityTrait;
}
