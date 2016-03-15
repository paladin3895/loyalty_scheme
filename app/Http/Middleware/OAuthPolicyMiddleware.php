<?php

namespace App\Http\Middleware;

use LucaDegasperi\OAuth2Server\Middleware\OAuthMiddleware;
use League\OAuth2\Server\Exception\InvalidScopeException;
use League\OAuth2\Server\Exception\AccessDeniedException;
use Closure;

use App\Exceptions\ExceptionResolver;

class OAuthPolicyMiddleware extends OAuthMiddleware
{

    public function handle($request, Closure $next, $scopesString = null)
    {
        try {
            return parent::handle($request, $next, $scopesString);
        } catch (\Exception $e) {
            if ($e instanceof InvalidScopeException) {
                throw ExceptionResolver::resolve('unauthorized', $e->getMessage());
            } elseif ($e instanceof AccessDeniedException) {
                throw ExceptionResolver::resolve('unauthorized', $e->getMessage());
            } else {
                throw $e;
            }

        }
    }

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
