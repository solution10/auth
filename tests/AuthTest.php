<?php

namespace Solution10\Auth\Tests;

use PHPUnit_Framework_TestCase;
use Solution10\Auth\Auth as Auth;
use Solution10\Auth\Tests\Mocks\Package;
use Solution10\Auth\Tests\Mocks\SessionDelegate as SessionDelegateMock;
use Solution10\Auth\Tests\Mocks\StorageDelegate as StorageDelegateMock;
use Solution10\Auth\Tests\Mocks\Package as PackageMock;
use Solution10\Auth\Tests\Mocks\UserRepresentation as UserRepMock;

/**
 * Tests for the Auth class
 */
class AuthTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var     Auth
     */
    protected $default_instance;

    /**
     * @var     SessionDelegateMock
     */
    protected $session_mock;

    /**
     * @var     StorageDelegateMock
     */
    protected $storage_mock;

    /**
     * Instantiates a basic instance:
     */
    public function setUp()
    {
        // Clear all previous instances:
        Auth::clearInstances();

        $this->session_mock = new SessionDelegateMock();
        $this->storage_mock = new StorageDelegateMock();
        $this->default_instance = new Auth(
            'default',
            $this->session_mock,
            $this->storage_mock,
            array(
                'cost' => 8,
            )
        );
    }

    /**
     * Returns a Mocked user object
     *
     * @param   int     $id     ID
     * @return  UserRepMock
     */
    protected function userMock($id)
    {
        return new UserRepMock(array(
            'id' => $id,
            'username' => 'User '.$id,
        ));
    }

    /**
     * Test construction
     */
    public function testConstructor()
    {
        $session = new SessionDelegateMock();
        $storage = new StorageDelegateMock();
        $auth = new Auth('default', $session, $storage, array(
            'cost' => 8,
        ));
        $this->assertTrue($auth instanceof Auth);
    }

    /**
     * Testing fetching the name of an instance
     */
    public function testName()
    {
        $this->assertEquals('default', $this->default_instance->name());
    }

    /**
     * Test instance grabbing and naming
     */
    public function testInstance()
    {
        $session = new SessionDelegateMock();
        $storage = new StorageDelegateMock();
        $defaultAuth = new Auth('default', $session, $storage);

        $this->assertEquals($defaultAuth, Auth::instance());
    }

    /**
     * Tests non-found instances
     *
     * @expectedException       \Solution10\ManagedInstance\Exception\InstanceException
     * @expectedExceptionCode   \Solution10\ManagedInstance\Exception\InstanceException::UNKNOWN_INSTANCE
     */
    public function testUnknownInstance()
    {
        $this->assertFalse(Auth::instance('unknown instance name'));
    }

    /**
     * Tests fetching all of the instances
     */
    public function testInstances()
    {
        Auth::clearInstances();
        $this->assertEquals(array(), Auth::instances());

        $session = new SessionDelegateMock();
        $storage = new StorageDelegateMock();

        $defaultAuth = new Auth('default', $session, $storage);
        $anotherAuth = new Auth('another', $session, $storage);

        $instances = Auth::instances();
        $this->assertCount(2, $instances);
        $this->assertArrayHasKey('default', $instances);
        $this->assertArrayHasKey('another', $instances);

        $this->assertEquals($defaultAuth, $instances['default']);
        $this->assertEquals($anotherAuth, $instances['another']);
    }

    /**
     * Test password hashing
     */
    public function testHashing()
    {
        $pass = 'fgjkdfhgdf77989';
        $hashed = $this->default_instance->hashPassword($pass);
        $this->assertEquals(60, strlen($hashed));
        $this->assertEquals(0, strpos($hashed, '$2a'));
    }

    /**
     * Test password checking
     */
    public function testPasswordCheck()
    {
        $pass = 'fjdggy744;0';
        $hashed = $this->default_instance->hashPassword($pass);
        $this->assertTrue($this->default_instance->checkPassword($pass, $hashed));
    }

    /**
     * Tests logging a user in successfully
     */
    public function testSuccessfulLogin()
    {
        $this->assertTrue($this->default_instance->login('Alex', 'Alex'));
    }

    /**
     * Test unsuccessful login
     */
    public function testUnsuccessfulLogin()
    {
        $this->assertFalse($this->default_instance->login('Alex', 'wrong-password'));
    }

    /**
     * Test login with a bad username
     */
    public function testLoginBadUsername()
    {
        $this->assertFalse($this->default_instance->login('Jenny', 'password'));
    }

    /**
     * Testing loggedIn()
     */
    public function testLoggedIn()
    {
        // Create a clean auth instance:
        $auth = new Auth('default', $this->session_mock, $this->storage_mock, array(
            'cost' => 8,
        ));

        $this->assertFalse($auth->loggedIn());

        $auth->login('Alex', 'Alex');
        $this->assertTrue($auth->loggedIn());
    }

    /**
     * Testing logout()
     */
    public function testLogout()
    {
        // Create a clean auth instance:
        $auth = new Auth('default', $this->session_mock, $this->storage_mock, array(
            'cost' => 8,
        ));

        $this->assertFalse($auth->loggedIn());

        $auth->login('Alex', 'Alex');
        $this->assertTrue($auth->loggedIn());

        $auth->logout();
        $this->assertFalse($auth->loggedIn());
    }

    /**
     * Testing the forceLogin() process with an ID
     */
    public function testForceLoginInt()
    {
        $auth = new Auth('default', $this->session_mock, $this->storage_mock, array(
            'cost' => 8,
        ));

        $this->assertFalse($auth->loggedIn());
        $this->assertTrue($auth->forceLogin(1));
        $this->assertTrue($auth->loggedIn());
        $this->assertEquals(new UserRepMock($this->storage_mock->users[1]), $auth->user());
    }

    /**
     * Testing the forceLogin() process with a user rep
     */
    public function testForceLoginUserRep()
    {
        $auth = new Auth('default', $this->session_mock, $this->storage_mock, array(
            'cost' => 8,
        ));

        $user_rep = new UserRepMock($this->storage_mock->users[1]);

        $this->assertFalse($auth->loggedIn());
        $this->assertTrue($auth->forceLogin($user_rep));
        $this->assertTrue($auth->loggedIn());
        $this->assertEquals($user_rep, $auth->user());
    }

    /**
     * Testing force login with an unknown user
     *
     * @expectedException       \Solution10\Auth\Exception\User
     * @expectedExceptionCode   \Solution10\Auth\Exception\User::USER_NOT_FOUND
     */
    public function testForceLoginUnknownUser()
    {
        $auth = new Auth('default', $this->session_mock, $this->storage_mock, array(
            'cost' => 8,
        ));

        $this->assertFalse($auth->loggedIn());
        $this->assertFalse($auth->forceLogin(10));
        $this->assertFalse($auth->loggedIn());
    }

    /**
     * Testing force login with a bad user rep
     */
    public function testForceLoginBadUserRep()
    {
        $auth = new Auth('default', $this->session_mock, $this->storage_mock, array(
            'cost' => 8,
        ));

        $user = (object)array(
            'id' => 'not an instance of UserRepresentation',
        );

        $this->assertFalse($auth->loggedIn());
        $this->assertFalse($auth->forceLogin($user));
        $this->assertFalse($auth->loggedIn());
    }


    /**
     * Testing fetching the user
     */
    public function testUser()
    {
        $auth = new Auth('default', $this->session_mock, $this->storage_mock, array(
            'cost' => 8,
        ));

        $auth->login('Alex', 'Alex');
        $user = $auth->user();
        $this->assertTrue($user instanceof \Solution10\Auth\UserRepresentation);
        $this->assertEquals(new UserRepMock($this->storage_mock->users[1]), $user);
    }

    /**
     * Testing fetching a user when not logged in
     */
    public function testUserNotLoggedIn()
    {
        $this->default_instance->logout();
        $this->assertFalse($this->default_instance->user());
    }

    /**
     * Testing when the storage can't find a user who claims to be logged in.
     */
    public function testUserGone()
    {
        $storage_mock = new StorageDelegateMock();
        $auth = new Auth('default', $this->session_mock, $storage_mock, array(
            'cost' => 8,
        ));

        $auth->login('Alex', 'Alex');

        // Everything should be fine at the moment:
        $this->assertTrue($auth->loggedIn());

        // Unset the user from storage, so when we call user(), they're gone.
        unset($storage_mock->users[1]);

        $this->assertFalse($auth->user());
        $this->assertFalse($auth->loggedIn());
    }


    /**
     * Testing adding a package to a user successfully
     */
    public function testAddPackageInstanceSuccessful()
    {
        $storage_mock = new StorageDelegateMock();
        $auth = new Auth('default', $this->session_mock, $storage_mock, array(
            'cost' => 8,
        ));

        $package = new PackageMock();
        $auth->addPackageToUser($this->userMock(1), $package);
        $this->assertEquals($package, $storage_mock->users[1]['packages'][0]);
    }

    /**
     * Testing adding a package by string name
     */
    public function testAddPackageStringSuccessful()
    {
        $storage_mock = new StorageDelegateMock();
        $auth = new Auth('default', $this->session_mock, $storage_mock, array(
            'cost' => 8,
        ));

        $package = 'Solution10\Auth\Tests\Mocks\Package';
        $auth->addPackageToUser($this->userMock(1), $package);
        $this->assertEquals($package, get_class($storage_mock->users[1]['packages'][0]));
    }

    /**
     * Testing adding a package that doesn't exist
     *
     * @expectedException        \Solution10\Auth\Exception\Package
     * @expectedExceptionCode    1
     */
    public function testAddPackageNotFound()
    {
        $storage_mock = new StorageDelegateMock();
        $auth = new Auth('default', $this->session_mock, $storage_mock, array(
            'cost' => 8,
        ));

        $package = 'Solution10\Auth\Tests\Mocks\PackageNotExist';
        $auth->addPackageToUser($this->userMock(1), $package);
    }

    /**
     * Testing adding a package that's not got Package as a parent.
     *
     * @expectedException        \Solution10\Auth\Exception\Package
     * @expectedExceptionCode    2
     */
    public function testAddPackageBadLineage()
    {
        $storage_mock = new StorageDelegateMock();
        $auth = new Auth('default', $this->session_mock, $storage_mock, array(
            'cost' => 8,
        ));

        $package = 'Solution10\Auth\Tests\Mocks\StorageDelegate';
        $auth->addPackageToUser($this->userMock(1), $package);
    }


    /**
     * Tests removing a package successfully
     */
    public function testRemovePackageInstanceSuccess()
    {
        $storage_mock = new StorageDelegateMock();
        $auth = new Auth('default', $this->session_mock, $storage_mock, array(
            'cost' => 8,
        ));

        $user = $this->userMock(1);

        $package = new Package();
        $auth->addPackageToUser($user, $package);

        // Now remove:
        $auth->removePackageFromUser($user, $package);
        $this->assertEquals(0, count($storage_mock->users[1]['packages']));
    }

    /**
     * Tests removing a package string successfully
     */
    public function testRemovePackageStringSuccess()
    {
        $storage_mock = new StorageDelegateMock();
        $auth = new Auth('default', $this->session_mock, $storage_mock, array(
            'cost' => 8,
        ));

        $user = $this->userMock(1);

        $package = '\Solution10\Auth\Tests\Mocks\Package';
        $auth->addPackageToUser($user, $package);

        // Now remove:
        $auth->removePackageFromUser($user, $package);
        $this->assertEquals(0, count($storage_mock->users[1]['packages']));
    }

    /**
     * Tests removing a package that doesn't exist
     * Should all be silent.
     */
    public function testRemovePackageNotFound()
    {
        $storage_mock = new StorageDelegateMock();
        $auth = new Auth('default', $this->session_mock, $storage_mock, array(
            'cost' => 8,
        ));

        $package = 'Solution10\Auth\Tests\Mocks\PackageNotFound';
        $this->assertEquals($auth, $auth->removePackageFromUser($this->userMock(1), $package));
    }

    /**
     * Tests fetching packages
     */
    public function testUserPackages()
    {
        $storage_mock = new StorageDelegateMock();
        $auth = new Auth('default', $this->session_mock, $storage_mock, array(
            'cost' => 8,
        ));

        $user = $this->userMock(1);

        $package = '\Solution10\Auth\Tests\Mocks\Package';
        $auth->addPackageToUser($user, $package);

        $this->assertEquals(array(new $package()), $storage_mock->users[1]['packages']);
    }

    /**
     * Tests fetching when there's no packages
     */
    public function testUserNoPackages()
    {
        $storage_mock = new StorageDelegateMock();
        $auth = new Auth('default', $this->session_mock, $storage_mock, array(
            'cost' => 8,
        ));

        $user = $this->userMock(1);

        $this->assertEquals($auth->packagesForUser($user), array());
    }

    /**
     * Testing if a user has a package
     */
    public function testUserHasPackageInstance()
    {
        $storage_mock = new StorageDelegateMock();
        $auth = new Auth('default', $this->session_mock, $storage_mock, array(
            'cost' => 8,
        ));

        $user = $this->userMock(1);

        $package = new Package();
        $auth->addPackageToUser($user, $package);

        $this->assertTrue($auth->userHasPackage($user, $package));
    }

    /**
     * Testing if a user has a package given by a string
     */
    public function testUserHasPackageString()
    {
        $storage_mock = new StorageDelegateMock();
        $auth = new Auth('default', $this->session_mock, $storage_mock, array(
            'cost' => 8,
        ));

        $user = $this->userMock(1);

        $package = '\Solution10\Auth\Tests\Mocks\Package';
        $auth->addPackageToUser($user, $package);

        $this->assertTrue($auth->userHasPackage($user, $package));
    }

    /**
     * Testing asking if a user has a package that don't exist
     */
    public function testUserHasPackageNotFound()
    {
        $storage_mock = new StorageDelegateMock();
        $auth = new Auth('default', $this->session_mock, $storage_mock, array(
            'cost' => 8,
        ));

        $package = '\Solution10\Auth\Tests\Mocks\PackageNotFound';
        $this->assertFalse($auth->userHasPackage($this->userMock(1), $package));
    }

    /**
     * Testing when asking if a user has a packag they haven't been assigned
     */
    public function testUserHasPackageNotAssigned()
    {
        $storage_mock = new StorageDelegateMock();
        $auth = new Auth('default', $this->session_mock, $storage_mock, array(
            'cost' => 8,
        ));

        $package = '\Solution10\Auth\Tests\Mocks\Package';
        $this->assertFalse($auth->userHasPackage($this->userMock(1), $package));
    }

    /**
     * Data setup for can() tests
     */
    protected function canInstance()
    {
        $storage_mock = new StorageDelegateMock();
        $auth = new Auth('default', $this->session_mock, $storage_mock, array(
            'cost' => 8,
        ));

        $auth->addPackageToUser($this->userMock(1), '\Solution10\Auth\Tests\Mocks\Package');
        return $auth;
    }


    /**
     * Basic userCan() tests on an instance with a single package
     */
    public function testCanBool()
    {
        $auth = $this->canInstance();
        $this->assertFalse($auth->userCan($this->userMock(1), 'login'));
    }

    public function testCanClosure()
    {
        $auth = $this->canInstance();
        $this->assertFalse($auth->userCan($this->userMock(1), 'closure', array('arg1', 'arg2')));
    }

    public function testCanInstance()
    {
        $auth = $this->canInstance();
        $this->assertFalse($auth->userCan($this->userMock(1), 'editPost'));
    }

    public function testCanStaticString()
    {
        $auth = $this->canInstance();
        $this->assertFalse($auth->userCan($this->userMock(1), 'staticString'));
    }

    public function testCanStaticArray()
    {
        $auth = $this->canInstance();
        $this->assertFalse($auth->userCan($this->userMock(1), 'staticArray'));
    }

    public function testCanClosureArgs()
    {
        $auth = $this->canInstance();
        $this->assertEquals('arg1arg2', $auth->userCan($this->userMock(1), 'closure_with_args', array('arg1', 'arg2')));
    }

    public function testCanUnknownPermission()
    {
        $auth = $this->canInstance();
        $this->assertFalse($auth->userCan($this->userMock(1), 'unknown_perm'));
    }

    /**
     * Tests userCan() when another package has overidden everything
     */
    protected function canHigherInstance()
    {
        $auth = $this->canInstance();
        $auth->addPackageToUser($this->userMock(1), 'Solution10\Auth\Tests\Mocks\HigherPackage');
        return $auth;
    }

    public function testHigherCanBool()
    {
        $auth = $this->canHigherInstance();
        $this->assertTrue($auth->userCan($this->userMock(1), 'login'));
    }

    public function testHigherCanClosure()
    {
        $auth = $this->canHigherInstance();
        $this->assertTrue($auth->userCan($this->userMock(1), 'closure', array('arg1', 'arg2')));
    }

    public function testHigherCanInstance()
    {
        $auth = $this->canHigherInstance();
        $this->assertTrue($auth->userCan($this->userMock(1), 'editPost'));
    }

    public function testHigherCanStaticString()
    {
        $auth = $this->canHigherInstance();
        $this->assertTrue($auth->userCan($this->userMock(1), 'staticString'));
    }

    public function testHigherCanStaticArray()
    {
        $auth = $this->canHigherInstance();
        $this->assertTrue($auth->userCan($this->userMock(1), 'staticArray'));
    }

    public function testHigherCanClosureArgs()
    {
        $auth = $this->canHigherInstance();
        $this->assertEquals('arg2arg1', $auth->userCan($this->userMock(1), 'closure_with_args', array('arg1', 'arg2')));
    }

    public function testHigherCanUnknownPermission()
    {
        $auth = $this->canHigherInstance();
        $this->assertFalse($auth->userCan($this->userMock(1), 'unknown_perm'));
    }

    /**
     * Tests that a higher package setting the same name rule or callback does come through.
     */
    public function testJumpingType()
    {
        $auth = $this->canHigherInstance();

        $user = $this->userMock(1);

        $this->assertTrue($auth->userCan($user, 'jumpTypeCallback'));
        $this->assertTrue($auth->userCan($user, 'jumpTypeRule'));

    }

    /**
     * Tests a package that is only partially overloaded
     */
    public function testPartiallyOverloadedPackage()
    {
        $auth = $this->canInstance();

        $user = $this->userMock(1);

        $auth->addPackageToUser($this->userMock(1), 'Solution10\Auth\Tests\Mocks\PartialPackage');

        $this->assertTrue($auth->userCan($user, 'login'));
        $this->assertTrue($auth->userCan($user, 'closure'));
        $this->assertTrue($auth->userCan($user, 'editPost'));
        $this->assertFalse($auth->userCan($user, 'staticString'));
        $this->assertFalse($auth->userCan($user, 'staticArray'));
        $this->assertEquals('arg1arg2', $auth->userCan($user, 'closure_with_args', array('arg1', 'arg2')));
        $this->assertFalse($auth->userCan($user, 'unknown_perm'));
    }

    /**
     * Tests rebuilding permissions when adding / removing packages
     */
    public function testRebuildingPermissions()
    {
        $auth = $this->canInstance();

        $user = $this->userMock(1);

        $this->assertFalse($auth->userCan($user, 'login'));

        $auth->addPackageToUser($user, 'Solution10\Auth\Tests\Mocks\HigherPackage');
        $this->assertTrue($auth->userCan($user, 'login'));

        $auth->removePackageFromUser($user, 'Solution10\Auth\Tests\Mocks\HigherPackage');
        $this->assertFalse($auth->userCan($user, 'login'));
    }

    /**
     * Tests can() on a user who is currently logged in
     */
    public function testCan()
    {
        $auth = $this->canInstance();
        $auth->addPackageToUser($this->userMock(1), 'Solution10\Auth\Tests\Mocks\PartialPackage');

        $auth->forceLogin(1);

        // Reusing the partial package tests as they cover everything
        $this->assertTrue($auth->can('login'));
        $this->assertTrue($auth->can('closure'));
        $this->assertTrue($auth->can('editPost'));
        $this->assertFalse($auth->can('staticString'));
        $this->assertFalse($auth->can('staticArray'));
        $this->assertEquals('arg1arg2', $auth->can('closure_with_args', array('arg1', 'arg2')));
        $this->assertFalse($auth->can('unknown_perm'));
    }

    /**
     * Testing can() when a user isn't logged in
     */
    public function testCanNotLoggedIn()
    {
        $auth = $this->canInstance();
        $auth->addPackageToUser($this->userMock(1), 'Solution10\Auth\Tests\Mocks\PartialPackage');

        // Reusing the partial package tests as they cover everything
        $this->assertFalse($auth->can('login'));
        $this->assertFalse($auth->can('closure'));
        $this->assertFalse($auth->can('editPost'));
        $this->assertFalse($auth->can('staticString'));
        $this->assertFalse($auth->can('staticArray'));
        $this->assertFalse($auth->can('closure_with_args', array('arg1', 'arg2')));
        $this->assertFalse($auth->can('unknown_perm'));
    }

    /**
     * Testing adding an override
     */
    public function testOverrideBasic()
    {
        $auth = $this->canInstance();

        $user = $this->userMock(1);

        $this->assertFalse($auth->userCan($user, 'login'));

        $auth->overridePermissionForUser($user, 'login', true);

        $this->assertTrue($auth->userCan($user, 'login'));
    }

    /**
     * Testing override with a permission the user currently does not know about.
     *
     * @expectedException       \Solution10\Auth\Exception\Override
     * @expectedExceptionCode   \Solution10\Auth\Exception\Override::UNKNOWN_PERMISSION
     */
    public function testOverrideUnknownPermission()
    {
        $auth = $this->canInstance();
        $auth->overridePermissionForUser($this->userMock(1), 'bad-permission', true);
    }

    /**
     * Testing removing an override
     */
    public function testRemoveOverride()
    {
        $auth = $this->canInstance();

        $user = $this->userMock(1);

        // Set up two overrides and then remove just one
        // to verify it's a localised effect
        $auth->overridePermissionForUser($user, 'login', true);
        $auth->overridePermissionForUser($user, 'logout', true);

        $this->assertTrue($auth->userCan($user, 'login'));
        $this->assertTrue($auth->userCan($user, 'logout'));

        // Now remove just login and see what happens:
        $auth->removeOverrideForUser($user, 'login');

        $this->assertFalse($auth->userCan($user, 'login'));
        $this->assertTrue($auth->userCan($user, 'logout'));
    }

    /**
     * Testing resetting the packages on a user after making changes
     */
    public function testResetUserPackages()
    {
        $auth = $this->canInstance();

        $user = $this->userMock(1);

        $this->assertFalse($auth->userCan($user, 'login'));
        $auth->overridePermissionForUser($user, 'login', true);
        $this->assertTrue($auth->userCan($user, 'login'));

        // Now reset and check it works:
        $auth->resetOverridesForUser($user);
        $this->assertFalse($auth->userCan($user, 'login'));
    }
}
