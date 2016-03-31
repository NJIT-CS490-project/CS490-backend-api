<?php

class RequestTest extends PHPUnit_Framework_TestCase {

    public function provider__construct () {
        return array(
            array( 'a', 'b', array( 'a' => 'b', 'b' => 'c'), array( 'c' => 'd', 'd' => 'e'))
				);
    }

    /**
     * @dataProvider provider__construct
     */
    public function test__construct ($path, $method, $headers, $params) {
        $req = new Request($path, $method, $headers, $params);
        $this->assertEquals($req->path,   $path);
        $this->assertEquals($req->method, $method);

        foreach ($headers as $key => $value) {
            $this->assertEquals($req->headers[$key], $value);
        }

        foreach ($params as $key => $value) {
            $this->assertEquals($req->params[$key], $value);
        }
    }

}
