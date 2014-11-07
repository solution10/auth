# Solution10\Auth

Powerful and extremely flexible authentication

[![Build Status](https://travis-ci.org/Solution10/auth.svg?branch=master)](https://travis-ci.org/Solution10/auth)
[![Coverage Status](https://coveralls.io/repos/Solution10/auth/badge.png)](https://coveralls.io/r/Solution10/auth)

[![Latest Stable Version](https://poser.pugx.org/Solution10/auth/v/stable.svg)](https://packagist.org/packages/Solution10/auth)
[![Total Downloads](https://poser.pugx.org/Solution10/auth/downloads.svg)](https://packagist.org/packages/Solution10/auth)
[![Latest Unstable Version](https://poser.pugx.org/Solution10/auth/v/unstable.svg)](https://packagist.org/packages/Solution10/auth)
[![License](https://poser.pugx.org/Solution10/auth/license.svg)](https://packagist.org/packages/Solution10/auth)

## Key Features

- **Framework agnostic**: <p>Can work with any framework and any ORM / database layer. Implement two classes to integrate with your tools of choice and Auth will do the rest.<br>[Learn more about Integration](http://github.com/solution10/auth/wiki/Integrating)</p>
- **Multiple Instances**: <p>No statics or manky global scope. Every auth instance is totally self contained and can talk to entirely different backends and session handlers.<br>[Learn more about Multiple Instances](http://github.com/solution10/auth/wiki/Instances)</p>
- **Powerful Permissions**: <p>Package based permissions allow you to define broad access control groups, and Overrides allow you to allow/disallow permissions on a per-user basis<br>[Learn more about Permissions](http://github.com/solution10/auth/wiki/Permissions)</p>

## Installation

Installation is as you'd expect, simply via a Composer requirement:

```json
{
    "require": {
        "solution10/auth": "~1.0"
    }
}
```

**Note**: Auth provides absolutely no storage capability (no database layer / access). You will need to provide this by implementing a
StorageDelegate. This approach might seem awkward at first, but it allows you to take completely control over the logic of data-retrieval,
whilst Auth handles the actual mechanics for you. [Learn more about Integration](http://github.com/solution10/auth/wiki/Integrating)

## Basic Usage

Your first step should be to complete everything in the [Integration guide](http://github.com/solution10/auth/wiki/Integrating), but that doesn't make for
a sexy demo, so we'll assume you've done that!

Let's pretend that I have fully implemented a StorageDelegate called "PDOStorageDelegate".

```php
// The storage delegate handles reading/writing User data from
// your data store. That could be a database, REST service, whatever.
$storageDelegate = new PDOStorageDelegate();

// The session delegate handles maintaining state between
// page loads. Essentially, it's a front to the $_SESSION array,
// but if you do it different, you can re-implement!
$sessionDelegate = new Solution10\Auth\Driver\Session();

// Fire up a new instance called "MyAuth"
$auth = new Solution10\Auth\Auth('MyAuth', $sessionDelegate, $storageDelegate);

// Play with some API methods:
if ($auth->loggedIn()) {
    echo 'Hi, '.$auth->user()->username.', welcome to the site!';
}
```

As you may have noticed, we give auth instances names. This gives us a way of referencing them later. More on
that in the [Instances](http://github.com/solution10/auth/wiki/Instances) chapter.

### Logging In

```php
if ($auth->login($username, $password)) {
    echo 'User was logged in!';
} else {
    echo 'Please check your username and password.';
}
```

### Logging Out

```php
$auth->logout();
```

### Checking Login State

```php
if ($auth->loggedIn())) {
    echo "You're logged in!";
} else {
    echo "You are not logged in :(";
}
```

### Getting the Current User

```php
$user = $auth->user();
```

### Forcing a Login

This should be used with extreme caution, it will allow you to log a user in without their password. Probably only useful after registration.

```php
// The $user object needs to be a class that implements the
// Solution10\Auth\UserRepresentation interface.
// It's a tiny interface, but it just gives us enough info to
// do our work.

$user = new UserRepresentationInstance();

$user->forceLogin($user);
```

You can read more about UserRepresentation in the [Integration guide](http://github.com/solution10/auth/wiki/Integrating)

## PHP Requirements

- PHP >= 5.4

## Documentation

For a user guide: [Check out the Wiki here on GitHub](http://github.com/solution10/auth/wiki).

For API docs, from a checkout of the project:

    $ make

Will dump an api/ folder in the root for you to peruse offline.

## Author

Alex Gisby: [GitHub](http://github.com/alexgisby), [Twitter](http://twitter.com/alexgisby)

## License

[MIT](http://github.com/solution10/auth/tree/master/LICENSE.md)

## Contributing

[Contributors Notes](http://github.com/solution10/auth/tree/master/CONTRIBUTING.md)
