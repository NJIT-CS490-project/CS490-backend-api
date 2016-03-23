<?php

require_once(__DIR__ . '/Response.php');

class HTTPException extends Exception implements Serializable, JsonSerializable {

    protected static $errorCode;
    protected static $errorMessage;

    public function __construct ($message = null, $code = null, $previous = null) {
        $message = $message === null ? static::$defaultMessage : $message;
        $code    = $code    === null ? static::$defaultCode    : $code;

        parent::__construct($message, $code, $previous);
    }

    /* Impelement Serializable */
    public function serialize () {
        return serialize([
            'message' => $this->getMessage()
        ]);
    }

    public function unserialize ($data) {
        $this->message = unserialize($data)['message'];
    }

    /* Implement JsonSerializable */
    public function jsonSerialize () {
        return [
            'message' => $this->getMessage()
        ];
    }
}

class MethodNotAllowedException extends HTTPException {

    protected static $defaultCode    = Response::RES_CODE_METHOD_NOT_ALLOWED;
    protected static $defaultMessage = 'That method is not allowed.';

}

class InternalServerException extends HTTPException {

    protected static $defaultCode    = Response::RES_CODE_INTERNAL_SERVER_ERROR;
    protected static $defaultMessage = 'An error has occurred.';
}
