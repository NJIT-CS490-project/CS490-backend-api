<?php

class MapTest extends PHPUnit_Framework_TestCase {

    public function provider__construct () {
        return [
            [
                [ 'a' => 'b'], ['a' => 'c']
            ]
        ];
    }

    /**
     * @dataProvider provider__construct
     */
    public function test__construct ($base) {
        $map = new Map($base);
    }

    /**
     * @dataProvider provider__construct
     */
    public function test_has ($base) {
        $map = new Map($base);

        foreach ($base as $key => $value) {
            $this->assertTrue($map->has($key));
        }
    }

    /**
     * @dataProvider provider__construct
     */
    public function test_get ($base) {
        $map = new Map($base);

        foreach ($base as $key => $value) {
            $this->assertEquals($map->get($key), $value);
        }
    }

    /**
     * @dataProvider provider__construct
     */
    public function test_arrayAccess ($base, $diff) {
        $map = new Map($base);

        foreach ($base as $key => $value) {
            $this->assertEquals($map[$key], $value);
        }

        foreach ($diff as $key => $value) {
            $map[$key]  = $value;
            $base[$key] = $value;
        }

        foreach ($base as $key => $value) {
            $this->assertEquals($map[$key], $value);
        }
    }

    /**
     * @dataProvider provider__construct
     */
    public function test_getOrThrow ($base) {
        $map = new Map($base);

        foreach ($base as $key => $value) {
            $this->assertEquals($map->get($key), $value);
        }
    }

    /**
     * @dataProvider provider__construct
     */
    public function test_set ($base, $diff) {
        $map = new Map($base);

        foreach ($base as $key => $value) {
            $this->assertEquals($map->get($key), $value);
        }

        foreach ($diff as $key => $value) {
            $map->set($key, $value);
            $base[$key] = $value;
        }

        foreach ($base as $key => $value) {
            $this->assertEquals($map->get($key), $value);
        }

    }

    /**
     * @dataProvider provider__construct
     */
    public function test_remove ($base, $diff) {
        $map = new Map($base);

        foreach ($base as $key => $value) {
            $this->assertEquals($map->get($key), $value);
        }

        foreach ($diff as $key => $value) {
            $map->remove($key);
            unset($base[$key]);
        }

        foreach ($base as $key => $value) {
            $this->assertEquals($map->get($key), $value);
        }

    }
    
}
