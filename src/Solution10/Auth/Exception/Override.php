<?php

namespace Solution10\Auth\Exception;

/**
 * Override Exception
 *
 * Exception throws when something goes wrong with overriding permissions
 * on a user
 *
 * @package       Solution10
 * @category      Auth
 * @author        Alex Gisby <alex@solution10.com>
 * @license       MIT
 */
class Override extends \Exception
{
    const USER_NOT_FOUND = 0;
    const UNKNOWN_PERMISSION = 1;
}
