<?php

namespace App;

use Dingo\Api\Dispatcher as DingoDispatcher;
use Dingo\Api\Http\InternalRequest;

class Dispatcher extends DingoDispatcher
{
    /**
     * Attempt to dispatch an internal request.
     *
     * @param \Dingo\Api\Http\InternalRequest $request
     *
     * @throws \Exception|\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface
     *
     * @return mixed
     */
    protected function dispatch(InternalRequest $request)
    {
        $this->routeStack[] = $this->router->getCurrentRoute();

        $this->clearCachedFacadeInstance();

        $this->container->instance('request', $request);

        $response = $this->router->dispatch($request);

        if (! $this->raw) {
            $response = $response->getOriginalContent();
        }

        $this->refreshRequestStack();

        return $response;
    }
}
