# JWT Auth

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

Found a bug? Please report it using the [issue tracker](https://github.com/andrewdyer/jwt-auth/issues), or better yet, fork the repository and submit a pull request.