<?php

require_once(__DIR__ . '/Map.php');

class Request {
    public $path;
    public $method;
    public $params;
    public $headers;

    public function __construct ($path, $method, $headers, $params) {
        $this->path   = $path;
        $this->method = $method;

        $this->headers = is_a($headers, 'Map')
            ? $headers
            : new Map($headers);

        $this->params = is_a($params, 'Map')
            ? $params
            : new Map($params);

    }

    /**
     * Generates a request based on the current apache variables.
     * @throws Exception
     */
    public static function generate () {
        $headers = new Map(apache_request_headers());
        $method  = $_SERVER['REQUEST_METHOD'];
        $path    = $_SERVER['REQUEST_URI'];

        switch ($headers->get('Content-Type', null)) {
            case 'application/json':
                $data   = file_get_contents('php://input');
                $values = json_decode($data, true);
                $params = new Map($values);
                break;
            case 'application/x-www-form-urlencoded':
                $params = new Map($_POST);
                break;
            default:
                if ($method === 'GET') {
                    $params = new Map($_GET);
                } else if ($method === 'POST' || $method === 'PUT') {
                    $params = new Map($_POST);
                } else {
                    $params = new Map();
                }
                break;
        }

        return new Request($path, $method, $headers, $params);
    }
}
