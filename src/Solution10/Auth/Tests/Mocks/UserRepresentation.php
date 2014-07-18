<?php

namespace Solution10\Auth\Tests\Mocks;

/**
 * User rep mock
 */
class UserRepresentation implements \Solution10\Auth\UserRepresentation
{
    protected $data = array();

    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    public function id()
    {
        return $this->data['id'];
    }
}
