<?php

namespace Solution10\Auth;

/**
 * Package Base Class
 *
 * Packages are groups of permissions which control what the user can and
 * cannot do. Packages contain rules and callbacks which can decide what
 * the user does. You can also edit the package on a per-user basis.
 *
 * @package       Solution10
 * @category      Auth
 * @author        Alex Gisby <alex@solution10.com>
 * @license       MIT
 */
abstract class Package
{
    /**
     * @var     array   Rules and callbacks.
     */
    protected $permissions = array();

    /**
     * @var   int    Precedence. How important this package is, higher numbers mean more important.
     */
    protected $precedence = 0;

    /**
     * Your package MUST implement init() to assign its rules and callbacks.
     */
    abstract public function init();

    /**
     * Your package must return a name for itself.
     *
     * @return    string
     */
    abstract public function name();

    /**
     * Constructor calls init()
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * ------------------- Adding rules and callbacks -----------------
     */

    /**
     * Adds a permission to the Package. Call from within init().
     *
     * @param   string          $name   Name of this permission
     * @param   bool|callable   $value  Bool for hard-rules or a callable for something more complex
     * @return  $this
     * @throws  Exception\Package
     */
    protected function permission($name, $value)
    {
        if (!is_bool($value) && !is_callable($value)) {
            throw new Exception\Package(
                'Value for permission "'.$name.'" is neither boolean nor callable.',
                Exception\Package::BAD_PERMISSION_VALUE
            );
        }

        $this->permissions[$name] = $value;
        return $this;
    }

    /**
     * Adds a group of permissions at once. key => value pairs.
     *
     * @param   array   $permissions    Permissions to add
     * @return  $this
     */
    protected function permissions(array $permissions)
    {
        foreach ($permissions as $name => $value) {
            $this->permission($name, $value);
        }
        return $this;
    }

    /**
     * ---------------- Fetching Rules and Callbacks -----------------
     */

    /**
     * Returns all of the rules and callbacks defined through 'permission()'.
     *
     * @return  array
     */
    public function definedPermissions()
    {
        return $this->permissions;
    }

    /**
     * ---------------- Precedence --------------------
     */

    /**
     * Gets / sets the precedence of the package.
     *
     * @param   int $precedence
     * @return  int|$this
     */
    public function precedence($precedence = null)
    {
        if ($precedence === null) {
            return $this->precedence;
        }

        $this->precedence = $precedence;
        return $this;
    }
}
