<?php

namespace Solution10\Auth\Tests\Mocks;

use Solution10\Auth\Package as BasePackage;

/**
 * General Package Mock
 */
class HigherPackage extends BasePackage
{
    public function name()
    {
        return 'HigherTestPackage';
    }

    public function init()
    {
        $this
            ->precedence(10)
            ->permission('login', true)
            ->permission('logout', true)
            ->permissions(
                array(
                    'view_profile'  => true,
                    'view_homepage' => true,
                )
            )
            ->permission('editPost', array($this, 'editPost'))
            ->permissions(
                array(
                    'staticString'     => __NAMESPACE__ . '\HigherPackage::staticString',
                    'staticArray'      => array(__NAMESPACE__ . '\HigherPackage', 'staticArray'),
                    'closure'           => function () {
                        return true;
                    },
                    'closure_with_args' => function ($user, $arg1, $arg2) {
                        return $arg2 . $arg1;
                    }
                )
            )
            ->permission('jumpTypeRule', function () {
                return true;
            })
            ->permission('jumpTypeCallback', true);

    }

    public function editPost()
    {
        return true;
    }

    public static function staticString()
    {
        return true;
    }

    public static function staticArray()
    {
        return true;
    }
}
