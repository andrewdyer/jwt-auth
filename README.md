![JWT Auth](https://raw.githubusercontent.com/andrewdyer/andrewdyer/refs/heads/main/assets/images/covers/jwt-auth.png)

# 🔑 JWT Auth

A simple framework-agnostic JSON Web Token authentication solution.

## 📄 License

Licensed under the [MIT license](https://opensource.org/licenses/MIT) and is free for private or commercial projects.

## 📥 Installation

```bash
composer require andrewdyer/jwt-auth
```

## 🚀 Getting Started

### 1. Define the JWT Subject

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

### 2. Create an Authentication Provider

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

### 3. Create a JWT Provider

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

### 4. Generate JWT Claims

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

### 5. Initialize the JWT Authenticator

Create a new instance of the `JWTAuth` class. This requires an instance of `AuthProviderInterface`, `JWTProviderInterface`, and `ClaimsInterface`.

```php
use App\Providers\AuthProvider;
use App\Providers\JWTProvider;
use Anddye\JWTAuth\JWTAuth;

$authProvider = new AuthProvider();

$jwtProvider = new JWTProvider();

$jwtAuth = new JWTAuth($authProvider, $jwtProvider, $claims);
```

## 📖 Usage

### Attempt Authentication

Authenticate a user by providing their credentials. If successful, a JWT token will be returned. If the credentials are invalid, an `InvalidCredentialsException` will be thrown.

```php
try {
    $token = $jwtAuth->attempt('admin', 'secret');
    echo "Token: " . $token;
} catch (\Anddye\JWTAuth\Exceptions\InvalidCredentialsException $e) {
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
