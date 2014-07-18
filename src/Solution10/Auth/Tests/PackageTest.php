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
            'editPost'         => array($package, 'editPost'),
            'staticString'     => 'Solution10\Auth\Tests\Mocks\Package::staticString',
            'staticArray'      => array('Solution10\Auth\Tests\Mocks\Package', 'staticArray'),
            'closure'           => function () {
                return false;
            },
            'closure_with_args' => function ($arg1, $arg2) {
                return $arg1 . $arg2;
            }
        );

        $retCallbacks = $package->callbacks();

        // hhvm won't pass this assert as the callback IDs it generates are different.
        //$this->assertEquals($callbacks, $retCallbacks);

        // So instead, let's check this manually:
        $this->assertEquals(array_keys($callbacks), array_keys($retCallbacks));
        $this->assertEquals($callbacks['editPost'], $retCallbacks['editPost']);
        $this->assertEquals($callbacks['staticString'], $retCallbacks['staticString']);
        $this->assertEquals($callbacks['staticArray'], $retCallbacks['staticArray']);

        // Verify the two closures came through:
        $this->assertFalse($retCallbacks['closure']());
        $this->assertEquals('arg1arg2', $retCallbacks['closure_with_args']('arg1', 'arg2'));
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
