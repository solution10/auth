<?php

namespace Solution10\Auth;

/**
 * The User representation Interface. All users_reps loaded from
 * StorageDelegate should conform to this protocol
 *
 * @package       S10
 * @category      Auth
 * @author        Alex Gisby <alex@solution10.com>
 * @license       MIT
 */
interface UserRepresentation
{
    /**
     * Returns the ID of this user. Doesn't matter what type it returns.
     *
     * @return    mixed
     */
    public function id();
}
