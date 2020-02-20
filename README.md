# JWT Auth

[![Latest Stable Version](https://poser.pugx.org/andrewdyer/jwt-auth/v/stable)](https://packagist.org/packages/andrewdyer/jwt-auth)
[![Total Downloads](https://poser.pugx.org/andrewdyer/jwt-auth/downloads)](https://packagist.org/packages/andrewdyer/jwt-auth)
[![Daily Downloads](https://poser.pugx.org/andrewdyer/jwt-auth/d/daily)](https://packagist.org/packages/andrewdyer/jwt-auth)
[![Monthly Downloads](https://poser.pugx.org/andrewdyer/jwt-auth/d/monthly)](https://packagist.org/packages/andrewdyer/jwt-auth)
[![Latest Unstable Version](https://poser.pugx.org/andrewdyer/jwt-auth/v/unstable)](https://packagist.org/packages/andrewdyer/jwt-auth)
[![License](https://poser.pugx.org/andrewdyer/jwt-auth/license)](https://packagist.org/packages/andrewdyer/jwt-auth)
[![composer.lock](https://poser.pugx.org/andrewdyer/jwt-auth/composerlock)](https://packagist.org/packages/andrewdyer/jwt-auth)

A simple framework agnostic JSON Web Token authentication solution.

## License
Licensed under MIT. Totally free for private or commercial projects.

## Installation
```text
composer require andrewdyer/jwt-auth
```

## Usage
```php
// Create a new auth provider instance
$authProvider = new App\Providers\AuthProvider();

// Create a new jwt provider instance
$jwtProvider = new App\Providers\JwtProvider();

// Build up jwt claims
$claimsFactory = new Anddye\JwtAuth\ClaimsFactory::build([
    'exp' => '',
    'iat' => '',
    'iss' => '',
    'jti' => '',
    'nbj' => '',
]);

// Bring everything together to create a jwt auth instance
$jwtAuth = new JwtAuth($authProvider, $jwtProvider, $claimsFactory);
```

### Auth Provider
```php
namespace App\Providers;

use Anddye\JwtAuth\Providers\AuthProviderInterface;

class AuthProvider implements AuthProviderInterface
{
    public function byCredentials(string $username, string $password)
    {
        // TODO: Validate username / password and return an instance of `Anddye\JwtAuth\Contracts\JwtSubject`
    }

    public function byId(int $id)
    {
        // TODO: Find a user by id and return an instance of `Anddye\JwtAuth\Contracts\JwtSubject` if exists
    }
}
```

### JWT Provider
```php
namespace Anddye\JwtAuth\Tests\Stubs\Providers;

use Anddye\JwtAuth\Providers\JwtProviderInterface;

class JwtProvider implements JwtProviderInterface
{
    public function decode(string $token)
    {
        // TODO: Decode JWT token somehow
    }

    public function encode(array $claims): string
    {
        // TODO: Encode claims and create a JWT token somehow
    }
}
```

### Attempt with credentials
```php
if (!$token = $jwtAuth->attempt($username, $password)) {
    // TODO: Handle failed attempt with credentials
} else {
    // TODO: Handle successful attempt with credentials
}
```

### Authenticate with token
```php
if (!$actor = $jwtAuth->authenticate($token)->getActor()) {
    // TODO: Handle failed authentication with token
} else {
    // TODO: Handle successful authentication with token
}
```

## Support
If you're using this package, I'd love to hear your thoughts! Feel free to contact me on [Twitter](https://twitter.com/andyer92).

Need to see an example? Check out [this tutorial](https://github.com/andrewdyer/jwt-auth/wiki/Slim-3-Example) on how to integrate this library into a [Slim 3](http://www.slimframework.com/docs/v3/) project.

Found a bug? Please report it using the [issue tracker](https://github.com/andrewdyer/jwt-auth/issues), or better yet, fork the repository and submit a pull request.
