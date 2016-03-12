<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

use App\Exceptions\ExceptionResolver;

class OAuthController extends BaseController
{
    public function authorize(Request $request)
    {
        try {
            return Authorizer::issueAccessToken();
        } catch (\Exception $e) {
            throw ExceptionResolver::resolve('bad request', $e->getMessage());
        }
    }

    public function verify($username, $password)
    {
        // @TODO implement logic for verify user authorization
    }

    public function info(Request $request)
    {
        // @TODO implement logic to get client info with endpoint /me
    }
}
