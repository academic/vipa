<?php

namespace Ojs\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OjsUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
