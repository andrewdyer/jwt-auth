![JWT-Auth](https://private-user-images.githubusercontent.com/8114523/397149152-40d30faf-e8e2-4513-8542-ecb889cecb28.png?jwt=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJnaXRodWIuY29tIiwiYXVkIjoicmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSIsImtleSI6ImtleTUiLCJleHAiOjE3MzQ1NjIyNzUsIm5iZiI6MTczNDU2MTk3NSwicGF0aCI6Ii84MTE0NTIzLzM5NzE0OTE1Mi00MGQzMGZhZi1lOGUyLTQ1MTMtODU0Mi1lY2I4ODljZWNiMjgucG5nP1gtQW16LUFsZ29yaXRobT1BV1M0LUhNQUMtU0hBMjU2JlgtQW16LUNyZWRlbnRpYWw9QUtJQVZDT0RZTFNBNTNQUUs0WkElMkYyMDI0MTIxOCUyRnVzLWVhc3QtMSUyRnMzJTJGYXdzNF9yZXF1ZXN0JlgtQW16LURhdGU9MjAyNDEyMThUMjI0NjE1WiZYLUFtei1FeHBpcmVzPTMwMCZYLUFtei1TaWduYXR1cmU9NjI4NTlmYTRmMTUzMmZhYTUwZWJiY2UwMDI0YWViNTYzYWM0NTRmNDQyOTg0M2I1MTA4Y2ZjNGYwMzVmOTA2NSZYLUFtei1TaWduZWRIZWFkZXJzPWhvc3QifQ.ceTxiv0kv7UaSXS9xYlfFhVBmZ__1B_X8s9gqsFJJek)

# JWT-Auth

A simple framework-agnostic JSON Web Token authentication solution.

[![Latest Stable Version](http://poser.pugx.org/andrewdyer/jwt-auth/v?style=for-the-badge)](https://packagist.org/packages/andrewdyer/jwt-auth) [![Total Downloads](http://poser.pugx.org/andrewdyer/jwt-auth/downloads?style=for-the-badge)](https://packagist.org/packages/andrewdyer/jwt-auth) [![Latest Unstable Version](http://poser.pugx.org/andrewdyer/jwt-auth/v/unstable?style=for-the-badge)](https://packagist.org/packages/andrewdyer/jwt-auth) [![License](http://poser.pugx.org/andrewdyer/jwt-auth/license?style=for-the-badge)](https://packagist.org/packages/andrewdyer/jwt-auth) [![PHP Version Require](http://poser.pugx.org/andrewdyer/jwt-auth/require/php?style=for-the-badge)](https://packagist.org/packages/andrewdyer/jwt-auth)

## Installation

```bash
composer require andrewdyer/jwt-auth
```

## Getting Started

### Define the JWT Subject

Create a class (e.g., `User`) that implements the `JWTSubject` interface. This class must provide a method `getJWTIdentifier` to return the user’s unique identifier.

```php
namespace App\Models;

use Anddye\JWTAuth\Interfaces\JWTSubject;

class User implements JWTSubject
{
    public function getJWTIdentifier(): int
    {
        return 1;
    }
}
```

> **Note:** This example is simplified for demonstration purposes. In a real-world application, you would typically use a proper user model, such as one provided by your framework. Ensure the `getJWTIdentifier` method returns a unique user identifier appropriate for your system.

### Create an Authentication Provider

Create an authentication provider class that implements `AuthProviderInterface`. This class will handle credential validation and user retrieval by ID.

```php
namespace App\Providers;

use Anddye\JWTAuth\Interfaces\AuthProviderInterface;
use App\Models\User;

class AuthProvider implements AuthProviderInterface
{
    public function byCredentials(string $username, string $password)
    {
        if ($username === 'admin' && $password === 'secret') {
            return new User();
        }

        return null;
    }

    public function byId(int $id)
    {
        if ($id === 1) {
            return new User();
        }

        return null;
    }
}
```

> **Note:** This example uses hardcoded credentials for demonstration purposes. In a real-world application, you should validate credentials securely by checking against a database and using hashed passwords (e.g., via libraries like `bcrypt` or `password_hash`). Ensure you follow best practices for secure authentication.

### Create a JWT Provider

Create a JWT provider class that implements `JWTProviderInterface`. This class should handle encoding and decoding JWT tokens.

```php
namespace App\Providers;

use Anddye\JWTAuth\Interfaces\JWTProviderInterface;

class JWTProvider implements JWTProviderInterface
{
    public function decode(string $token)
    {
        return json_decode(base64_decode($token), true);
    }

    public function encode(array $claims): string
    {
        return base64_encode(json_encode($claims));
    }
}
```

> **Note:** This examples used `base64_encode` and `base64_decode` for simplicity. For real-world usage, consider using a proper JWT library such as [firebase/php-jwt](https://github.com/firebase/php-jwt) for better security.

### Generate JWT Claims

The `ClaimsFactory` class helps create a JWT claims instance. The `build` method accepts an array of claims and returns an instance of `ClaimsInterface`.

```php
use Anddye\JWTAuth\Factory\ClaimsFactory;

$claims = ClaimsFactory::build([
    'iss' => 'https://example.com',     // Issuer of the JWT
    'aud' => 'https://example.com',     // Audience of the JWT
    'exp' => 1582243200,                // Expiration time (Unix timestamp)
    'nbf' => 1582193571,                // Not before time (Unix timestamp)
    'iat' => 1582193571,                // Issued at time (Unix timestamp)
    'jti' => 'fVcx9BJHqh',              // Unique identifier
]);
```

> **Note:** This example uses hardcoded Unix timestamps for demonstration purposes. Consider using libraries like [nesbot/carbon](https://github.com/briannesbitt/carbon) or PHP's native `DateTime` class to generate timestamps dynamically. This helps improve readability and ensures accurate date handling.

### Initialize the JWT Authenticator

Create a new instance of the `JWTAuth` class. This requires an instance of `AuthProviderInterface`, `JWTProviderInterface`, and `ClaimsInterface`.

```php
use App\Providers\AuthProvider;
use App\Providers\JWTProvider;
use Anddye\JWTAuth\JWTAuth;

$authProvider = new AuthProvider();

$jwtProvider = new JWTProvider();

$jwtAuth = new JWTAuth($authProvider, $jwtProvider, $claims);
```

## Usage

### Attempt Authentication

Authenticate a user by providing their credentials. If successful, a JWT token will be returned.

```php
$token = $jwtAuth->attempt('admin', 'secret');

if ($token) {
    echo "Token: " . $token;
} else {
    echo "Invalid credentials";
}
```

### Authenticate a Token

Validate a JWT token and retrieve the associated user (subject).

```php
$subject = $jwtAuth->authenticate('your-jwt-token-here');

if ($subject) {
    echo "User authenticated!";
} else {
    echo "Invalid token";
}
```

## License

Licensed under MIT. Totally free for private or commercial projects.
