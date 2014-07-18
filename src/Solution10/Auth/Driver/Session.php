<?php

namespace Solution10\Auth\Driver;

use Solution10\Auth\SessionDelegate as SessionDelegate;

/**
 * Native PHP Session driver for Auth.
 *
 * Provides a reference implementation of the SessionDelegate class.
 * Uses built in $_SESSION for storage. Simples.
 *
 * @package       Solution10
 * @category      Auth\Driver
 * @author        Alex Gisby <alex@solution10.com>
 * @license       MIT
 */
class Session implements SessionDelegate
{
    /**
     * Reads the authentication data out of the session for a given named instance.
     *
     * @param   string  $instance_name    Instance name
     * @return  string  Auth data string from the cookie / session etc
     */
    public function authRead($instance_name)
    {
        return (isset($_SESSION['s10auth'][$instance_name])) ?
            $_SESSION['s10auth'][$instance_name] :
            false;
    }

    /**
     * Writes the authentication data into the session.
     *
     * @param   string  $instance_name  Name of the Auth instance to write.
     * @param   string  $auth_data      Encrypted data to write to the store.
     * @return  bool    True for success, false for failure.
     */
    public function authWrite($instance_name, $auth_data)
    {
        $_SESSION['s10auth'][$instance_name] = $auth_data;
        return true;
    }

    /**
     * Deletes a value from the persistent store
     *
     * @param   string  $instance_name  Name of the instance to void
     * @return  bool
     */
    public function authDelete($instance_name)
    {
        unset($_SESSION['s10auth'][$instance_name]);
        return true;
    }
}
