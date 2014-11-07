# Solution10\Auth

Powerful and flexible authentication that can drop in anywhere.

## Key Features

- **Framework agnostic**: <p>Can work with any framework and any ORM / database layer. Implement two classes to integrate with your tools of choice and Auth will do the rest.<br>[Learn more about Integration](Integrating)</p>
- **Multiple Instances**: <p>No statics or manky global scope. Every auth instance is totally self contained and can talk to entirely different backends and session handlers.<br>[Learn more about Multiple Instances](Instances)</p>
- **Powerful Permissions**: <p>Package based permissions allow you to define broad access control groups, and Overrides allow you to allow/disallow permissions on a per-user basis<br>[Learn more about Permissions](Permissions)</p>

## Installation

Installation is as you'd expect, simply via a Composer requirement:

```json
"require": {
    "solution10/auth": "1.*"
}
```

**Note**: Auth provides absolutely no storage capability (no database layer / access). You will need to provide this by implementing a StorageDelegate. This approach might seem awkward at first, but it allows you to take completely control over the logic of data-retrieval, whilst Auth handles the actual mechanics for you. [Learn more about Integration](Integrating)

## Basic Usage

Your first step should be to complete everything in the [Integration guide](Integrating), but that doesn't make for
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
	
As you may have noticed, we give auth instances names. This gives us a way of referencing them later. More on that in the [Instances](Instances) chapter.

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
	
You can read more about UserRepresentation in the [Integration guide](Integrating)

## Read More

That's the basics covered, but we haven't even scratched the surface of [Powerful Permissions](Permissions) or the
wonders of [Multiple Instances](Instances)
