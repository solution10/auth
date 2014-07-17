<?php

namespace Solution10\Auth\Tests;

use PHPUnit_Framework_TestCase;
use Solution10\Auth\Package as Package;
use Solution10\Auth\Tests\Mocks\Package as PackageMock;

/**
 * Tests for the Package class
 */
class PackageTest extends PHPUnit_Framework_TestCase
{
    /**
     * Testing construction
     */
    public function testConstructor()
    {
        $package = new PackageMock();
        $this->assertTrue($package instanceof Package);
    }

    /**
     * Testing adding the callbacks
     */
    public function testAddingCallbacks()
    {
        // The callbacks are added in the init() of PackageMock.
        $package = new PackageMock();
        $package->init(); // Init is usually called by Auth, you shouldn't call it
        // directly from your classes.

        $callbacks = array(
            'edit_post'         => array($package, 'edit_post'),
            'static_string'     => 'Solution10\Auth\Tests\Mocks\Package::static_string',
            'static_array'      => array('Solution10\Auth\Tests\Mocks\Package', 'static_array'),
            'closure'           => function () {
                return false;
            },
            'closure_with_args' => function ($arg1, $arg2) {
                return $arg1 . $arg2;
            }
        );

        $this->assertEquals($callbacks, $package->callbacks());
    }

    /**
     * Testing adding rules
     */
    public function testAddingRules()
    {
        $package = new PackageMock();
        $package->init();

        $rules = array(
            'login'         => false,
            'logout'        => false,
            'view_profile'  => true,
            'view_homepage' => false,
        );

        $this->assertEquals($rules, $package->rules());
    }

    /**
     * Testing precedence
     */
    public function testPrecedence()
    {
        $package = new PackageMock();

        $this->assertTrue($package->precedence(5) instanceof PackageMock);
        $this->assertEquals(5, $package->precedence());
    }

    /**
     * Testing the package name
     */
    public function testName()
    {
        $package = new PackageMock();
        $this->assertEquals('TestPackage', $package->name());
    }
}
