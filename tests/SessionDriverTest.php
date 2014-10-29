<?php

namespace Solution10\Auth\Tests;

use PHPUnit_Framework_TestCase;
use Solution10\Auth\Driver\Session as SessionDelegate;

class SessionDelegateTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $_SESSION = array();
    }

    public function testAuthRead()
    {
        $d = new SessionDelegate();

        $this->assertFalse($d->authRead('authtest'));

        // Insert some data and run again
        $_SESSION['s10auth']['authtest'] = 'Test Data';
        $this->assertEquals('Test Data', $d->authRead('authtest'));
    }

    public function testAuthWrite()
    {
        $d = new SessionDelegate();
        $d->authWrite('authtest', 'Test Data');
        $this->assertEquals('Test Data', $_SESSION['s10auth']['authtest']);
    }

    public function testAuthDelete()
    {
        $d = new SessionDelegate();
        $d->authWrite('authtest', 'Test Data');
        $this->assertEquals('Test Data', $_SESSION['s10auth']['authtest']);

        // Now remove it:
        $d->authDelete('authtest');
        $this->assertArrayNotHasKey('authtest', $_SESSION['s10auth']);
    }
}
