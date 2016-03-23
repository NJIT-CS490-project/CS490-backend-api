<?php

class Response {

    const RESCODE_OK = 200;

    public $code = 200;

    public function __construct ($body, $code = 200, $headers = []) {
        $this->body = $body;
        $this->code = $code;
        $this->headers = array_merge($headers, $this->headers);
    }
}
