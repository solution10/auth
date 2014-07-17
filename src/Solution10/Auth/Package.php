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
     * @var    array    Rules for this package. Rules are straight yes/no responses.
     */
    protected $rules = array();

    /**
     * @var    array    Callbacks for this package. Anything callable
     */
    protected $callbacks = array();

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
     * Adds a rule into the Package. Call from within init().
     *
     * @param   string  $name   Name of this rule
     * @param   bool    $value  Rule value
     * @return  $this   Chainable
     */
    protected function addRule($name, $value)
    {
        $this->rules[$name] = (bool)$value;
        return $this;
    }

    /**
     * Adds a whole bunch of rules at once. Array of name => value pairs.
     * Call from within init()
     *
     * @param   array   $rules  Array of rules
     * @return  $this   Chainable
     */
    protected function addRules(array $rules)
    {
        foreach ($rules as $name => $value) {
            $this->addRule($name, $value);
        }

        return $this;
    }

    /**
     * Adds a callback into the Package
     *
     * @param   string      $name       Name of this callback rule
     * @param   callable    $callback   Callback to add. Anything callable.
     * @return  $this   Chainable
     */
    protected function addCallback($name, $callback)
    {
        $this->callbacks[$name] = $callback;
        return $this;
    }

    /**
     * Adds multiple callbacks into the Package
     *
     * @param   array   $callbacks  Callbacks to add
     * @return  $this   Chainable
     */
    protected function addCallbacks(array $callbacks)
    {
        foreach ($callbacks as $name => $callback) {
            $this->addCallback($name, $callback);
        }

        return $this;
    }

    /**
     * ---------------- Fetching Rules and Callbacks -----------------
     */

    /**
     * Fetching the rules for this package
     *
     * @return  array
     */
    public function rules()
    {
        return $this->rules;
    }

    /**
     * Fetching the callbacks
     *
     * @return   array
     */
    public function callbacks()
    {
        return $this->callbacks;
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
