<?php

namespace Solution10\Auth\Tests;

use PHPUnit_Framework_TestCase;
use Solution10\Auth\Package as Package;
use Solution10\Auth\Tests\Mocks\Package as PackageMock;
use Solution10\Auth\Tests\Mocks\UserRepresentation as UserRepMock;
use Solution10\Auth\Tests\Mocks\BadPackage;

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
     * Test that all of the permissions survive initialisation.
     */
    public function testAddingPermissions()
    {
        // The callbacks are added in the init() of PackageMock.
        $package = new PackageMock();

        $packagePerms = array(
            'login'         => false,
            'logout'        => false,
            'view_profile'  => true,
            'view_homepage' => false,
            'jumpTypeRule'  => false,
            'editPost'         => array($package, 'editPost'),
            'staticString'     => 'Solution10\Auth\Tests\Mocks\Package::staticString',
            'staticArray'      => array('Solution10\Auth\Tests\Mocks\Package', 'staticArray'),
            'closure'           => function () {
                return false;
            },
            'closure_with_args' => function ($user, $arg1, $arg2) {
                return $arg1 . $arg2;
            },
            'jumpTypeCallback' => function ($user) {
                return false;
            }
        );

        $perms = $package->definedPermissions();

        // Check that the rules have all come through correctly:
        foreach ($packagePerms as $name => $value) {
            $this->assertArrayHasKey($name, $perms);

            if ($packagePerms[$name] instanceof \Closure === false) {
                $this->assertEquals($packagePerms[$name], $perms[$name]);
            }
        }

        // We have to check these two closures separately to keep HHVM happy when running the tests.
        $this->assertFalse($perms['closure']());
        $this->assertEquals('arg1arg2', $perms['closure_with_args'](new UserRepMock(), 'arg1', 'arg2'));
    }

    /**
     * Testing adding a permission with a bad value
     *
     * @expectedException       \Solution10\Auth\Exception\Package
     * @expectedExceptionCode   \Solution10\Auth\Exception\Package::BAD_PERMISSION_VALUE
     */
    public function testAddBadPermissionValue()
    {
        new BadPackage();
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
        $this->assertEquals(PackageMock::class, $package->name());
    }
}
