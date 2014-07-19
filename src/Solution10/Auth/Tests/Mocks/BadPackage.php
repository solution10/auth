<?php

namespace Solution10\Auth\Tests\Mocks;

use Solution10\Auth\Package as BasePackage;

class BadPackage extends BasePackage
{
    public function name()
    {
        return 'BadPackage';
    }

    public function init()
    {
        $this
            ->permission('bad', 'string value is not good!');
    }
}
