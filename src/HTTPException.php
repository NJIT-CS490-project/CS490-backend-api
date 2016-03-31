<?php

require_once(__DIR__ . '/Response.php');

class HTTPException extends Exception implements Serializable {

    protected static $defaultCode    = Response::RES_CODE_METHOD_NOT_ALLOWED;
    protected static $defaultMessage = '';

    public function __construct ($message = null, $code = null, $previous = null) {
			$message = $message === null ? static::$defaultMessage : $message;
      $code    = $code    === null ? static::$defaultCode    : $code;

      parent::__construct($message, $code, $previous);
    }

    /* Impelement Serializable */
    public function serialize () {
        return serialize(array(
            'message' => $this->getMessage()
				));
    }

    public function unserialize ($data) {
        $tmp = unserialize($data);
				$this->message = $tmp['message'];
    }

    /* Implement JsonSerializable */
    public function jsonSerialize () {
        return array(
            'message' => $this->getMessage()
				);
    }
}

class ParameterNotFoundException extends HTTPException {
		protected static $defaultCode    = Response::RES_CODE_BAD_REQUEST;
		protected static $defaultMessage = 'Parameter not found.';
}

class ConflictExistsException extends HTTPException {
	protected static $defaultCode      = Response::RES_CODE_CONFLICT;
	protected static $defaultMessage   = 'Cannot perform request due to conflict.';
}

class MethodNotAllowedException extends HTTPException {
    protected static $defaultCode    = Response::RES_CODE_METHOD_NOT_ALLOWED;
    protected static $defaultMessage = 'That method is not allowed.';
}

class InternalServerException extends HTTPException {
    protected static $defaultCode    = Response::RES_CODE_INTERNAL_SERVER_ERROR;
    protected static $defaultMessage = 'An error has occurred.';
}

class UnauthorizedException extends HTTPException {
		protected static $defaultCode    = Response::RES_CODE_UNAUTHORIZED;
		protected static $defaultMessage = 'You are not authorized to perform that action.';
}
