<?php
namespace App\Exceptions;

class ExceptionResolver
{
    public static function resolve($type, $message)
    {
        switch (strtolower($type)) {
            case 'bad request':
                return new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException($message);
            case 'unauthorized':
                return new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException($message);
            case 'access denied':
                return new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException($message);
            case 'not found':
                return new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException($message);
            case 'method not allowed':
                return new \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException([], $message);
            case 'not acceptable':
                return new \Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException($message);
            case 'conflict':
                return new \Symfony\Component\HttpKernel\Exception\ConflictHttpException($message);
            case 'gone':
                return new \Symfony\Component\HttpKernel\Exception\GoneHttpException($message);
            case 'length required':
                return new \Symfony\Component\HttpKernel\Exception\LengthRequiredHttpException($message);
            case 'precondition failed':
                return new \Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException($message);
            case 'unsupported media type':
                return new \Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException($message);
            case 'precondition required':
                return new \Symfony\Component\HttpKernel\Exception\PreconditionRequiredHttpException($message);
            case 'too many request':
                return new \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException($message);
            case 'resource':
                return new \Dingo\Api\Exception\ResourceException($message);
            case 'service unavailable':
                return new \Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException($message);
            default:
                return new \Symfony\Component\HttpKernel\Exception\HttpException($message);
        }
    }
}
