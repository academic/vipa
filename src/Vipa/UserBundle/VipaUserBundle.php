<?php

namespace Vipa\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class VipaUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
