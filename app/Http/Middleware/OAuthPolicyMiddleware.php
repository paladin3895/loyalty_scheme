<?php

namespace App\Http\Middleware;

use LucaDegasperi\OAuth2Server\Middleware\OAuthMiddleware;
use League\OAuth2\Server\Exception\InvalidScopeException;
use Closure;


class OAuthPolicyMiddleware extends OAuthMiddleware
{
    /**
     * Validate the scopes.
     *
     * @param $scopes
     *
     * @throws \League\OAuth2\Server\Exception\InvalidScopeException
     */
    public function validateScopes($required_scopes)
    {
        $scopes = array_keys($this->authorizer->getScopes());

        foreach ($required_scopes as $required) {

            reset($scopes);
            foreach ($scopes as $scope) {
                if (strpos($required, $scope) !== false) continue 2;
            }

            throw new InvalidScopeException(implode(',', $scopes));
        }
    }
}
