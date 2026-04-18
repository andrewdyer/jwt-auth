![JWT Auth](https://public-assets.andrewdyer.rocks/images/covers/jwt-auth.png)

<p align="center">
  <a href="https://packagist.org/packages/andrewdyer/jwt-auth"><img src="https://poser.pugx.org/andrewdyer/jwt-auth/v/stable?style=for-the-badge" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/andrewdyer/jwt-auth"><img src="https://poser.pugx.org/andrewdyer/jwt-auth/downloads?style=for-the-badge" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/andrewdyer/jwt-auth"><img src="https://poser.pugx.org/andrewdyer/jwt-auth/license?style=for-the-badge" alt="License"></a>
  <a href="https://packagist.org/packages/andrewdyer/jwt-auth"><img src="https://poser.pugx.org/andrewdyer/jwt-auth/require/php?style=for-the-badge" alt="PHP Version Required"></a>
</p>

# JWT Auth

A framework-agnostic PHP JWT authentication library for managing tokens, built around contracts for user resolution and claims generation.

## Introduction

This library provides a clean, contract-driven approach to JSON Web Token authentication by coordinating token issuance and parsing while delegating token handling, user resolution, and claims generation to user-defined implementations. By relying on simple interfaces, it remains fully framework-agnostic and unopinionated, allowing integration with any authentication system or JWT library.

## Prerequisites

- **[PHP](https://www.php.net/)**: Version 8.3 or higher is required.
- **[Composer](https://getcomposer.org/)**: Dependency management tool for PHP.

## Installation

```bash
composer require andrewdyer/jwt-auth
```

## Getting Started

### 1. Implement the JWT subject

Any class that represents an authenticated user or entity must implement `JwtSubjectInterface`. This provides the identifier that will be stored in the token's `sub` claim.

```php
use AndrewDyer\JwtAuth\Contracts\JwtSubjectInterface;

class User implements JwtSubjectInterface
{
    public function __construct(private int $id) {}

    public function getJwtIdentifier(): int|string
    {
        return $this->id;
    }
}
```

### 2. Implement the auth provider

A class implementing `AuthProviderInterface` must be provided to resolve users by credentials or by ID. `JwtAuth` calls these methods internally during `attempt()` and `authenticate()`.

```php
use AndrewDyer\JwtAuth\Contracts\AuthProviderInterface;

class MyAuthProvider implements AuthProviderInterface
{
    public function byCredentials(string $username, string $password): ?JwtSubjectInterface
    {
        // Return a JwtSubjectInterface instance on success, or null on failure
    }

    public function byId(int|string $id): ?JwtSubjectInterface
    {
        // Return a JwtSubjectInterface instance, or null if not found
    }
}
```

### 3. Implement the JWT provider

A class implementing `JwtProviderInterface` handles token encoding and decoding. This is the integration point for a preferred JWT library such as [`firebase/php-jwt`](https://github.com/firebase/php-jwt) or [`lcobucci/jwt`](https://github.com/lcobucci/jwt).

```php
use AndrewDyer\JwtAuth\Contracts\JwtProviderInterface;

class MyJwtProvider implements JwtProviderInterface
{
    public function encode(array $claims): string
    {
        // Encode the claims array into a signed token string
    }

    public function decode(string $token): mixed
    {
        // Decode and verify the token; return the payload as an array or object
    }
}
```

### 4. Implement the claims factory

A class implementing `ClaimsFactoryInterface` builds the JWT claims for a given user. The `iat`, `nbf`, and `exp` fields accept plain Unix timestamps from `time()`, [Carbon](https://github.com/briannesbitt/Carbon), or any other source.

```php
use AndrewDyer\JwtAuth\Claims;
use AndrewDyer\JwtAuth\Contracts\ClaimsFactoryInterface;
use AndrewDyer\JwtAuth\Contracts\JwtSubjectInterface;

class MyClaimsFactory implements ClaimsFactoryInterface
{
    public function forSubject(JwtSubjectInterface $subject): Claims
    {
        $now = time();

        return new Claims(
            iss: 'my-app',
            aud: 'my-api',
            iat: $now,
            nbf: $now,
            exp: $now + 3600,
            jti: bin2hex(random_bytes(16)),
            sub: $subject->getJwtIdentifier(),
        );
    }
}
```

When using Carbon, `Carbon::now()->timestamp` is a drop-in replacement for `time()`.

## Usage

### Create a JwtAuth instance

The three dependencies are wired up to create a `JwtAuth` instance:

```php
use AndrewDyer\JwtAuth\JwtAuth;

$auth = new JwtAuth(
    authProvider: new MyAuthProvider(),
    jwtProvider: new MyJwtProvider(),
    claimsFactory: new MyClaimsFactory(),
);
```

### Attempt a login

Validates a username and password and returns a signed token. Throws `InvalidCredentialsException` if the credentials are invalid.

```php
use AndrewDyer\JwtAuth\Exceptions\InvalidCredentialsException;

try {
    $token = $auth->attempt('user@example.com', 'secret');
} catch (InvalidCredentialsException $e) {
    // Credentials did not match a valid user
}
```

### Authenticate a token

Decodes a token, verifies it, and returns the corresponding user. Throws `InvalidTokenException` if the token is invalid or the user cannot be found.

```php
use AndrewDyer\JwtAuth\Exceptions\InvalidTokenException;

try {
    $user = $auth->authenticate($token);
} catch (InvalidTokenException $e) {
    // Token is invalid or the user no longer exists
}
```

### Parse a token

Decodes a token into a `Claims` object without looking up the user.

```php
use AndrewDyer\JwtAuth\Exceptions\InvalidTokenException;

try {
    $claims = $auth->parse($token);

    echo $claims->sub; // The subject identifier
    echo $claims->iss; // The issuer
    echo $claims->exp; // Expiry timestamp
} catch (InvalidTokenException $e) {
    // Token could not be decoded
}
```

## Claims

The `Claims` class is a read-only value object representing the payload of a JWT. It exposes the standard registered claims as typed public properties:

| Property | Type          | Description                  |
| -------- | ------------- | ---------------------------- |
| `iss`    | `string`      | Issuer                       |
| `aud`    | `?string`     | Audience                     |
| `iat`    | `int`         | Issued-at timestamp          |
| `nbf`    | `int`         | Not-before timestamp         |
| `exp`    | `int`         | Expiry timestamp             |
| `jti`    | `string`      | Unique token identifier      |
| `sub`    | `int\|string` | Subject identifier (user ID) |
| `custom` | `array`       | Any additional custom claims |

A `Claims` instance can be serialized back to an array using `toArray()`, which omits null values:

```php
$array = $claims->toArray();
```

A `Claims` instance can also be constructed directly from an array. The claims `iss`, `iat`, `nbf`, `exp`, `jti`, and `sub` are all required; `aud` is optional and defaults to `null` if omitted. All values must match their expected types — throws `InvalidTokenException` if any required claim is missing or any claim has an invalid type:

```php
$claims = Claims::fromArray([
    'iss' => 'my-app',
    'aud' => 'my-api',
    'iat' => 1711324800,
    'nbf' => 1711324800,
    'exp' => 1711328400,
    'jti' => 'abc123',
    'sub' => 42,
]);
```

Any keys not in the standard set are captured in the `custom` array.

## Exceptions

| Exception                     | Thrown when                                                                                                                     |
| ----------------------------- | ------------------------------------------------------------------------------------------------------------------------------- |
| `InvalidCredentialsException` | `attempt()` is called and the credentials do not resolve to a valid user                                                        |
| `InvalidTokenException`       | A token cannot be decoded, required claims are missing or have invalid types, or the subject cannot be resolved to a valid user |

Both extend `RuntimeException`.

## License

Licensed under the [MIT license](https://opensource.org/licenses/MIT) and is free for private or commercial projects.
