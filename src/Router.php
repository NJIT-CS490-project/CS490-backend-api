<?php

require_once(__DIR__ . '/HTTPException.php');
require_once(__DIR__ . '/Request.php');
require_once(__DIR__ . '/Response.php');

class Router {

    private $services        = [];
    private $handlers        = [];
    private $views           = [];
    private $errorHandlers   = [];

    public function __construct (
        $services      = [],
        $handlers      = [],
        $views         = [],
        $errorHandlers = []
    ) {
        $this->services = array_merge($services, $this->services);
        $this->handlers = array_merge($handlers, $this->handlers);
    }

    public function registerService ($name, $service) {
        $this->services[$name] = $service;
    }

    public function on ($method, $handler) {
        $this->handlers[$method] = $handler;
    }

    public function route (Request $request = null) {
        $response = null;

        try {
            if ($request === null) {
                $request = Request::generate();
            }

            if (!array_key_exists($request->method, $this->handlers)) {
                $response = $this->handlers[$request->method]($request, $this->services);
            }
        } catch (HTTPException $e) {
            $response = new Response($e, $e->getCode());
        } catch (Exception $e) {
            $e = new InternalServerException(null, null, $e);
            $response = new Response($e, $e->getCode());
        }

        $response = $this->normalizeResponse($response);

        http_response_code($response->code);

        foreach ($response as $key => $value) {
            header($key . ':' . $value);
        }

        var_dump($response);

        echo json_encode($response);
    }

    private function renderResponse ($response = null) {
        if ($response === null) {
            return new Response();
        }

        switch (gettype($response)) {
            case 'array':
            case 'boolean':
            case 'int':
            case 'double':
                return new Response(json_encode($response));
            case 'object':
                if (is_a($response, 'Response')) {
                    return $response;
                } else {
                    return new Response(json_encode($response));
                }
            default:
                return new Response(json_encode($resonse));
        };
    }

}
