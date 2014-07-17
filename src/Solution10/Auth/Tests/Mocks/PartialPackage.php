<?php

namespace Solution10\Auth\Tests\Mocks;

/**
 * General Package Mock
 */
class PartialPackage extends \Solution10\Auth\Package
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
			->add_callback('edit_post', array($this, 'edit_post'))
			->add_callbacks(array(
					'closure' => function() {
						return true;
					},
				));

	}

	public function edit_post()
	{
		return true;
	}
}