# JWT Auth

A lightweight, framework-agnostic JWT authentication library for PHP.

## Introduction

This library provides a clean, interface-driven approach to JSON Web Token authentication. It handles token generation, validation, and user authentication without coupling your code to any specific framework or JWT library. All key behaviours — encoding/decoding tokens, looking up users, and tracking time — are provided through contracts that you implement, making it easy to integrate with any stack.

## Installation

```bash
composer require andrewdyer/jwt-auth
```

Requires PHP 8.3 or newer.

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

Create a class implementing `AuthProviderInterface` that resolves users by credentials or by ID. `JwtAuth` calls these methods internally during `attempt()`, `authenticate()`, and `refresh()`.

```php
use AndrewDyer\JwtAuth\Contracts\AuthProviderInterface;

class MyAuthProvider implements AuthProviderInterface
{
    public function byCredentials(string $username, string $password): mixed
    {
        // Return a JwtSubjectInterface instance on success, or null on failure
    }

    public function byId(int|string $id): mixed
    {
        // Return a JwtSubjectInterface instance, or null if not found
    }
}
```

### 3. Implement the JWT provider

Create a class implementing `JwtProviderInterface` to handle token encoding and decoding. This is where you plug in your preferred JWT library such as [`firebase/php-jwt`](https://github.com/firebase/php-jwt) or [`lcobucci/jwt`](https://github.com/lcobucci/jwt).

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

    public function decodeUnverified(string $token): mixed
    {
        // Decode without signature verification (used during token refresh)
    }
}
```

### 4. Set up the claims factory

The built-in `DefaultClaimsFactory` handles standard JWT claims automatically. It requires a `ClockInterface` implementation to provide the current time.

```php
use AndrewDyer\JwtAuth\Contracts\ClockInterface;

class SystemClock implements ClockInterface
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
```

Then instantiate the factory:

```php
use AndrewDyer\JwtAuth\DefaultClaimsFactory;

$claimsFactory = new DefaultClaimsFactory(
    clock: new SystemClock(),
    issuer: 'my-app',
    audience: 'my-api',
    ttl: 3600,
    notBeforeGrace: 0,
);
```

`DefaultClaimsFactory` accepts the following constructor arguments:

| Parameter        | Type             | Default | Description                                        |
| ---------------- | ---------------- | ------- | -------------------------------------------------- |
| `clock`          | `ClockInterface` | —       | Provides the current time                          |
| `issuer`         | `string`         | `'app'` | The `iss` claim value                              |
| `audience`       | `?string`        | `null`  | The `aud` claim value (omitted if `null`)          |
| `ttl`            | `int`            | `3600`  | Token lifetime in seconds                          |
| `notBeforeGrace` | `int`            | `0`     | Seconds after issue before the token becomes valid |

If `DefaultClaimsFactory` does not meet your needs, you can implement `ClaimsFactoryInterface` directly, for example to include custom claims:

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
            custom: ['role' => 'admin'],
        );
    }
}
```

## Usage

### Create a JwtAuth instance

Wire up the three dependencies and create a `JwtAuth` instance:

```php
use AndrewDyer\JwtAuth\JwtAuth;

$auth = new JwtAuth(
    authProvider: new MyAuthProvider(),
    jwtProvider: new MyJwtProvider(),
    claimsFactory: $claimsFactory,
);
```

### Attempt a login

Validate a username and password and return a signed token. Throws `InvalidCredentialsException` if the credentials are invalid.

```php
use AndrewDyer\JwtAuth\Exceptions\InvalidCredentialsException;

try {
    $token = $auth->attempt('user@example.com', 'secret');
} catch (InvalidCredentialsException $e) {
    // Credentials did not match a valid user
}
```

### Authenticate a token

Decode a token, verify it, and return the corresponding user. Throws `InvalidTokenException` if the token is invalid or the user cannot be found.

```php
use AndrewDyer\JwtAuth\Exceptions\InvalidTokenException;

try {
    $user = $auth->authenticate($token);
} catch (InvalidTokenException $e) {
    // Token is invalid or the user no longer exists
}
```

### Parse a token

Decode a token into a `Claims` object without looking up the user.

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

### Refresh a token

Decode the token without full verification, look up the user, and issue a fresh token. Useful for extending sessions without requiring a full re-login. Throws `InvalidTokenException` if the token payload is unusable or the user cannot be found.

```php
use AndrewDyer\JwtAuth\Exceptions\InvalidTokenException;

try {
    $newToken = $auth->refresh($token);
} catch (InvalidTokenException $e) {
    // Token could not be refreshed
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

You can serialize a `Claims` instance back to an array using `toArray()`, which omits null values:

```php
$array = $claims->toArray();
```

You can also construct a `Claims` instance directly from an array:

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

| Exception                     | Thrown when                                                                  |
| ----------------------------- | ---------------------------------------------------------------------------- |
| `InvalidCredentialsException` | `attempt()` is called and the credentials do not resolve to a valid user     |
| `InvalidTokenException`       | A token cannot be decoded, or the subject cannot be resolved to a valid user |

Both extend `RuntimeException`.

## License

Licensed under the [MIT license](https://opensource.org/licenses/MIT) and is free for private or commercial projects.
