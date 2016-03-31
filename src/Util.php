<?php

class Util {

    public static function requireParameter ($key, $location) {

        if (!array_key_exists('username', $parameters)) {
            http_response_code(400);
            echo json_encode(array(
                'message' => 'Missing parameter: username'
						));
            die();
        }
        
    }
}
