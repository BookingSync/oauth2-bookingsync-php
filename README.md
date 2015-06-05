# BookingSync Provider for OAuth 2.0 Client
[![Latest Version](https://img.shields.io/github/release/BookingSync/oauth2-bookingsync-php.svg?style=flat-square)](https://github.com/bookingsync/oauth2-bookingsync-php/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/BookingSync/oauth2-bookingsync-php/master.svg?style=flat-square)](https://travis-ci.org/bookingsync/oauth2-bookingsync-php)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/bookingsync/oauth2-bookingsync-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/bookingsync/oauth2-bookingsync-php/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/bookingsync/oauth2-bookingsync-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/bookingsync/oauth2-bookingsync-php)
[![Total Downloads](https://img.shields.io/packagist/dt/bookingsync/oauth2-bookingsync-php.svg?style=flat-square)](https://packagist.org/packages/bookingsync/oauth2-bookingsync-php)

This package provides BookingSync OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require bookingsync/oauth2-bookingsync-php
```

## Usage

Usage is the same as The League's OAuth client, using `\Bookingsync\OAuth2\Client\Provider\Bookingsync` as the provider.

### Authorization Code Flow

```php
$provider = new Bookingsync\OAuth2\Client\Provider\Bookingsync([
    'clientId'          => '{bookingsync-client-id}',
    'clientSecret'      => '{bookingsync-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url'
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->state;
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $userDetails = $provider->getUserDetails($token);

        // Use these details to create a new profile
        printf('Hello %s!', $userDetails->screenName);

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->accessToken;
}
```

### Refreshing a Token

```php
$provider = new Bookingsync\OAuth2\Client\Provider\Bookingsync([
    'clientId'          => '{bookingsync-client-id}',
    'clientSecret'      => '{bookingsync-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url'
]);

$grant = new \League\OAuth2\Client\Grant\RefreshToken();
$token = $provider->getAccessToken($grant, ['refresh_token' => $refreshToken]);
```

## Testing

```
phpunit test
```

## License

The MIT License (MIT). Please see [License File](https://github.com/bookingsync/oauth2-bookingsync-php/blob/master/LICENSE) for more information.