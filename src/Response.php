<?php

class Response {

	const RES_CODE_OK                    = 200;
	const RES_CODE_METHOD_NOT_ALLOWED    = 405;
  const RES_CODE_INTERNAL_SERVER_ERROR = 500;
	const RES_CODE_BAD_REQUEST           = 400;
	const RES_CODE_UNAUTHORIZED          = 401;
	const RES_CODE_CONFLICT              = 409;

	public $code;
	public $body;
	public $headers = array();

	public function __construct ($body = '', $code = 200, $headers = array()) {
		$this->body = $body;
		$this->code = $code;
		$this->headers = array_merge($this->headers, $headers);
	}
}
