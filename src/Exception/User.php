<?php

namespace Solution10\Auth\Exception;

/**
 * User Exception
 *
 * Exception throws when something goes wrong with fetching
 * user information.
 *
 * @package       Solution10
 * @category      Auth
 * @author        Alex Gisby <alex@solution10.com>
 * @license       MIT
 */
class User extends \Exception
{
    const USER_NOT_FOUND = 0;
}
