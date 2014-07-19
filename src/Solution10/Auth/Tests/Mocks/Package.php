<?php

namespace Solution10\Auth\Tests\Mocks;

use Solution10\Auth\Package as BasePackage;

/**
 * General Package Mock
 */
class Package extends BasePackage
{
    public function init()
    {
        $this
            ->permission('login', false)
            ->permission('logout', false)
            ->permissions(
                array(
                    'view_profile'  => true,
                    'view_homepage' => false,
                )
            )
            ->permission('editPost', array($this, 'editPost'))
            ->permissions(
                array(
                    'staticString'      => __NAMESPACE__ . '\Package::staticString',
                    'staticArray'       => array(__NAMESPACE__ . '\Package', 'staticArray'),
                    'closure'           => function () {
                        return false;
                    },
                    'closure_with_args' => function ($user, $arg1, $arg2) {
                        return $arg1 . $arg2;
                    }
                )
            )
            ->permission('jumpTypeRule', false)
            ->permission('jumpTypeCallback', function () {
                return false;
            });

    }

    public function name()
    {
        return 'TestPackage';
    }

    public function editPost()
    {
        return false;
    }

    public static function staticString()
    {
        return false;
    }

    public static function staticArray()
    {
        return false;
    }
}
