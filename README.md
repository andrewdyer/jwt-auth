<p align="center">
  <img src="https://raw.githubusercontent.com/andrewdyer/jwt-auth/b14d72673a415ec56184636ffcbb91b26c3d1c2b/.github/logo.png" alt="JWT Auth" />
</p>

<p align="center">
    <a href="https://packagist.org/packages/andrewdyer/jwt-auth"><img src="https://poser.pugx.org/andrewdyer/jwt-auth/downloads?style=for-the-badge" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/andrewdyer/jwt-auth"><img src="https://poser.pugx.org/andrewdyer/jwt-auth/v?style=for-the-badge" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/andrewdyer/jwt-auth"><img src="https://poser.pugx.org/andrewdyer/jwt-auth/license?style=for-the-badge" alt="License"></a>
</p>

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
    'exp' => 1582243200, // Friday, 21 February 2020 00:00:00
    'iat' => 1582193571, // Thursday, 20 February 2020 10:12:51
    'iss' => 'https://example.com',
    'jti' => 'fVcx9BJHqh',
    'nbj' => '1582193571', // Thursday, 20 February 2020 10:12:51
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

### Claims Factory
| Option | Type | Description |
| --- | --- | --- |
| exp | int | Time after which the JWT expires. |
| iat | int | Time at which the JWT was issued. |
| iss | string | Issuer of the JWT. |
| jti | string | Unique identifier; can be used to prevent the JWT from being replayed. |
| nbj | int | Time before which the JWT must not be accepted for processing. |

```php
$claimsFactory = new Anddye\JwtAuth\ClaimsFactory();
$claimsFactory->setExp(1582243200); // Friday, 21 February 2020 00:00:00
$claimsFactory->setIat(1582193571); // Thursday, 20 February 2020 10:12:51
$claimsFactory->setIss('https://example.com');
$claimsFactory->setJti('fVcx9BJHqh');
$claimsFactory->setNbj(1582193571); // Thursday, 20 February 2020 10:12:51
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
