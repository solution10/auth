<?php

namespace Solution10\Auth;

/**
 * Storage Delegate Interface
 *
 * Put simply, this is the class that reads and writes from the database
 * on behalf of Auth. Means you could use MySQL, Mongo, flat files, whatever
 * you want. Agnosticism for the win!
 *
 * @package       Solution10
 * @category      Auth
 * @author        Alex Gisby <alex@solution10.com>
 * @license       MIT
 */
interface StorageDelegate
{
    /**
     * Fetches a user by their username. This function should return either an
     * array containing:
     *  - id: the unique identifier for this user
     *    - username: the username we just looked up
     *    - password: the hashed version of the users password.
     * If it's a success, or false if there's no user by that name
     *
     * @param   string  $instance_name  Instance name
     * @param   string  $username       Username to search for
     * @return  array|bool
     */
    public function authFetchUserByUsername($instance_name, $username);

    /**
     * Fetches the full user representation of a given ID. ie your active record
     * instance or the like.
     *
     * @param   string  $instance_name  Instance name
     * @param   int     $user_id        ID of the logged in user
     * @return  UserRepresentation      The representation you return must implement the UserRepresentation interface.
     */
    public function authFetchUserRepresentation($instance_name, $user_id);

    /**
     * Adding a package to a given user.
     *
     * @param   string              $instance_name      Auth instance name
     * @param   UserRepresentation  $user               User representation (taken from authFetchUserRepresentation)
     * @param   Package             $package            Package to add.
     * @return  bool
     */
    public function authAddPackageToUser($instance_name, UserRepresentation $user, Package $package);

    /**
     * Removing a package from a given user.
     *
     * @param   string              $instance_name  Auth instance name
     * @param   UserRepresentation  $user           User representation (taken from authFetchUserRepresentation)
     * @param   Package             $package        Package to remove.
     * @return  bool
     */
    public function authRemovePackageFromUser($instance_name, UserRepresentation $user, Package $package);

    /**
     * Fetching all packages for a user
     *
     * @param   string              $instance_name  Auth instance name
     * @param   UserRepresentation  $user           User representation (taken from authFetchUserRepresentation)
     * @return  array
     */
    public function authFetchPackagesForUser($instance_name, UserRepresentation $user);

    /**
     * Returns whether a user has a given package or not.
     *
     * @param   string              $instance_name      Auth instance name
     * @param   UserRepresentation  $user               User representation
     * @param   Package             $package            Package to check for
     * @return  bool
     */
    public function authUserHasPackage($instance_name, UserRepresentation $user, Package $package);

    /**
     * Stores an overridden permission for a user
     *
     * @param   string              $instance_name  Auth instance name
     * @param   UserRepresentation  $user
     * @param   string              $permission     Permission name
     * @param   bool                $new_value      New value
     * @return  bool
     */
    public function authOverridePermissionForUser(
        $instance_name,
        UserRepresentation $user,
        $permission,
        $new_value
    );

    /**
     * Fetches all the permission overrides for a given user.
     *
     * @param   string              $instance_name    Auth instance name
     * @param   UserRepresentation  $user
     * @return  array   An array of permission => (bool) values
     */
    public function authFetchOverridesForUser($instance_name, UserRepresentation $user);

    /**
     * Removes all the overrides for a given user.
     *
     * @param   string              $instance_name    Auth instance name
     * @param   UserRepresentation  $user
     * @return  bool
     */
    public function authResetOverridesForUser($instance_name, UserRepresentation $user);
}
