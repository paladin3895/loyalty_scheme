<?php

namespace App\Http\Controllers;

use Liquid\Schema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exceptions\ExceptionResolver;

use App\Models\Event;
use App\Models\Entity;
use App\Formatters\EventFormatter;
use App\Http\Helpers;

class EventController extends SingularController
{
    protected $endpoint = 'event';

    public function __construct(Event $repository, EventFormatter $formatter)
    {
        parent::__construct($repository, $formatter);
    }

    public function apply($id, Request $request)
    {
        $event = $this->repository->find($id);
        if (!$event) {
            throw ExceptionResolver::resolve('not found', "{$this->endpoint} not found");
        }

        if (!$request->has('target')) {
            throw ExceptionResolver::resolve('bad request', "request with no target entity");
        }
        $target = (int)$request->input('target');
        $entity = Entity::find($target);
        if (!$entity) {
            throw ExceptionResolver::resolve('not found', "target entity not found");
        }

        foreach ($event->condition as $key => $value) {
            if (!isset($entity->$key) || ($entity->$key !== $value)) {
                throw ExceptionResolver::resolve('not acceptable', 'entity not match the event condition');
            }
        }

        foreach ($event->content as $key => $value) {
            if (!isset($entity->$key)) {
                $entity->$key = $value;
            } else {
                $entity->$key = Helpers::policyCompute($entity->$key, $value);
            }
        }

        $response = [
            'status' => 1,
            'results' => [],
            'errors' => [],
        ];
        $dispatcher = $this->api;
        foreach ($request->headers as $key => $value) {
            $dispatcher->header($key, implode(',', $value));
        }

        $subscribers = $event->subscribers()->orderBy('priority', 'desc')->get()->toArray();
        foreach ($subscribers as $subscriber) {
            $result = $this->api->be(app('Dingo\Api\Auth\Auth')->user())
                                ->with(['target' => $target])
                                ->raw()
                                ->post("api/v1/schema/{$subscriber['schema_id']}")
                                ->getOriginalContent();

            if ($result['status']) {
                $response['results'][] = $result['result'];
            } else {
                $response['errors'][] = $result['error'];
            }
        }

        return $this->response->array($response);
    }
}
