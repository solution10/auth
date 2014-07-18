<?php

namespace Solution10\Auth\Tests\Mocks;

use Solution10\Auth\Package as BasePackage;

/**
 * General Package Mock
 */
class PartialPackage extends BasePackage
{
    public function name()
    {
        return 'HigherTestPackage';
    }

    public function init()
    {
        $this
            ->precedence(10)
            ->addRule('login', true)
            ->addCallback('editPost', array($this, 'editPost'))
            ->addCallbacks(
                array(
                    'closure' => function () {
                        return true;
                    },
                )
            );

    }

    public function editPost()
    {
        return true;
    }
}
