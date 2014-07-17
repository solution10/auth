<?php

namespace Solution10\Auth\Tests\Mocks;

/**
 * General Package Mock
 */
class HigherPackage extends \Solution10\Auth\Package
{
	public function name()
	{
		return 'HigherTestPackage';
	}

	public function init()
	{
		$this
			->precedence(10)
			->add_rule('login', true)
			->add_rule('logout', true)
			->add_rules(array(
					'view_profile' => true,
					'view_homepage' => true,
			  	))
			->add_callback('edit_post', array($this, 'edit_post'))
			->add_callbacks(array(
					'static_string' => __NAMESPACE__ . '\HigherPackage::static_string',
					'static_array' 	=> array(__NAMESPACE__ . '\HigherPackage', 'static_array'),
					'closure' => function() {
						return true;
					},
					'closure_with_args' => function($arg1, $arg2) {
						return $arg2 . $arg1;
					}
				));

	}

	public function edit_post()
	{
		return true;
	}

	public static function static_string()
	{
		return true;
	}

	public static function static_array()
	{
		return true;
	}
}