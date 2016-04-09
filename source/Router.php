<?php

require_once(__DIR__ . '/HTTPException.php');
require_once(__DIR__ . '/Request.php');
require_once(__DIR__ . '/Response.php');
require_once(__DIR__ . '/Database.php');

class Router {

    private $services        = array();
    private $handlers        = array();
    private $views           = array();
    private $errorHandlers   = array();

    public function __construct (
        $services      = array(),
        $handlers      = array(),
        $views         = array(),
        $errorHandlers = array()
    ) {
        $this->services = array_merge($services, $this->services);
        $this->handlers = array_merge($handlers, $this->handlers);
    }

    public function register ($name, $service) {
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

						$request->method = strtolower($request->method);
									
						if (!array_key_exists($request->method, $this->handlers) || $this->handlers[$request->method] === null) {
							throw new MethodNotAllowedException();
						}
						
						$handler = $this->handlers[$request->method];
						$response = $handler($request, $this->services);
            
        } catch (HTTPException $e) {	
					$response = $e;
        } catch (Exception $e) {
						throw $e;
            $response = new InternalServerException(null, null, $e);
        }


        $response = $this->normalizeResponse($response);

        http_response_code($response->code);

        foreach ($response->headers as $key => $value) {
            header($key . ':' . $value);
        }

				header('Content-Type: application/json; charset=UTF-8');

        echo $this->encodeResponse($response);
    }

    private function normalizeResponse ($response = null) {
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
                } else if (is_a($response, 'HTTPException')) {
										return new Response(array(
												'message' => $response->getMessage(),
												'code'    => $response->getCode()
										), $response->getCode());
								} else {
                    return new Response(json_encode($response));
                }
            default:
                return new Response(json_encode($response));
        };
    }

		private function encodeResponse ($response) {
			if ($response->body === null) {
				return '';
			}
			
			switch (gettype($response->body)) {
				case 'array':
				case 'object':
					return json_encode($response->body);
				default:
					return $response->body;
			}
		}
}
