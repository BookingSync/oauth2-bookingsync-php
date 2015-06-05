<?php namespace Bookingsync\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Token\AccessToken;

class Bookingsync extends AbstractProvider
{
    public $scopeSeparator = ' ';
    public $scopes = ['public', 'bookings_write_owned', 'bookings_read', 'bookings_write',
                      'clients_read', 'clients_write',
                      'inquiries_read', 'inquiries_write',
                      'payments_read', 'payments_write',
                      'rates_read', 'rates_write',
                      'rentals_read', 'rentals_write',
                      'reviews_write'];
    public $responseType = 'json';
    public $authorizationHeader = 'Bearer';
    public $version = 'v3';

    /**
     * Get the URL that this provider uses to begin authorization.
     *
     * @return string
     */
    public function urlAuthorize()
    {
        return 'https://www.bookingsync.com/oauth/authorize';
    }

    /**
     * Get the URL that this provider users to request an access token.
     *
     * @return string
     */
    public function urlAccessToken()
    {
        return 'https://www.bookingsync.com/oauth/token';
    }

    /**
     * Get the URL that this provider uses to request user details.
     *
     * Since this URL is typically an authorized route, most providers will require you to pass the access_token as
     * a parameter to the request. For example, the google url is:
     *
     * 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token='.$token
     *
     * @param AccessToken $token
     * @return string
     */
    public function urlUserDetails(AccessToken $token)
    {
        return 'https://www.bookingsync.com/api/'.$this->version.'/accounts';
    }

    /**
     * Given an object response from the server, process the user details into a format expected by the user
     * of the client.
     *
     * @param object $response
     * @param AccessToken $token
     * @return mixed
     */
    public function userDetails($response, AccessToken $token)
    {
        $user = new User();

        $user->exchangeArray([
            'uid' => isset($response->id) ? $response->id : null,
            'business_name' => isset($response->business_name) ? $response->business_name : null,
            'email' => isset($response->email) ? $response->email : null
        ]);

        return $user;
    }

    public function userScreenName($response, AccessToken $token)
    {
        return isset($response->business_name) && $response->business_name ? $response->business_name : null;
    }
}
