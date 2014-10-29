<?php

namespace Solution10\Auth;

/**
 * Session Delegate.
 *
 * You need to implement this to allow for persistence between calls
 * to your app. Usually just integrate with your frameworks session
 * handler.
 *
 * @package       Solution10
 * @category      Auth
 * @author        Alex Gisby <alex@solution10.com>
 * @license       MIT
 */
interface SessionDelegate
{
    /**
     * Reads the authentication data out of the session for a given named instance.
     *
     * @param   string  $instance_name    Instance name
     * @return  string  Auth data string from the cookie / session etc
     */
    public function authRead($instance_name);

    /**
     * Writes the authentication data into the session.
     *
     * @param   string  $instance_name  Name of the Auth instance to write.
     * @param   string  $auth_data      Encrypted data to write to the store.
     * @return  bool    True for success, false for failure.
     */
    public function authWrite($instance_name, $auth_data);

    /**
     * Deletes a value from the persistent store
     *
     * @param   string  $instance_name  Name of the instance to void
     * @return  bool
     */
    public function authDelete($instance_name);
}
