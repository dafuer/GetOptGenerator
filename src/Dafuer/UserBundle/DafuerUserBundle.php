<?php

namespace Dafuer\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DafuerUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
