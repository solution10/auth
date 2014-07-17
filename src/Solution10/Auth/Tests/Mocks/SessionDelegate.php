<?php

namespace Solution10\Auth\Tests\Mocks;

/**
 * Persistent Store test Mock
 */
class SessionDelegate implements \Solution10\Auth\SessionDelegate
{
	protected $storage = array();

	/**
	 * Reads the authentication data out of the session for a given named instance.
	 *
	 * @param 	string 			Instance name
	 * @return 	string|false 	Auth data string from the cookie / session etc
	 */
	public function authRead($instance_name)
	{
		return (array_key_exists($instance_name, $this->storage))? $this->storage[$instance_name] : false;
	}

	/**
	 * Writes the authentication data into the session.
	 * 
	 * @param  string $instance_name Name of the Auth instance to write.
	 * @param  string $auth_data     Encrypted data to write to the store.
	 * @return bool 	True for success, false for failure.
	 */
	public function authWrite($instance_name, $auth_data)
	{
		$this->storage[$instance_name] = $auth_data;
		return true;
	}

	/**
	 * Deletes a value from the persistent store
	 *
	 * @param  string 	$instance_name 	Name of the instance to void
	 * @return void
	 */
	public function authDelete($instance_name)
	{
		unset($this->storage[$instance_name]);
	}
}