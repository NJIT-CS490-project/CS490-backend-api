<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Map implements ArrayAccess, Serializable {

    private $values = array();

    /**
     * Creates a new Bag of values.
     * @param array $base The values this bag should contain.
     */
    public function __construct ($base = array()) {
			if (gettype($base) === 'array') {
				$this->values = array_merge($this->values, $base);
			}
    }

    public function has ($key) {
			return array_key_exists($key, $this->values);
    }

    public function get ($key, $default = null) {
        if ($this->has($key)) {
            return $this->values[$key];
        } else {
            return $default;
        }
    }

    public function mustHave ($key) {
			if (gettype($key) === 'array') {
				foreach ($key as $subkey) {
					$this->mustHave($subkey);
				}
				return null;
			} else if ($this->has($key)) {
					return $this->values[$key];
			} else {
					throw new ParameterNotFoundException('Failed to find parameter: ' . $key);
      }
    }

    public function set ($key, $value, $overwrite = true) {
        if ($overwrite || !$this->has($key)) {
            $this->values[$key] = $value;
        }
    }

    public function remove ($key) {
        unset($this->values[$key]);
    }

    public function pop ($key, $default = null) {
        $val = $this->get($key, $defualt);
        $this->remove($key);
        return $val;
    }

    /* Implement ArrayAccess */
    public function offsetExists ($key) {
        return $this->has($key);
    }

    public function offsetGet ($key) {
        return $this->get($key);
    }

    public function offsetSet ($key, $value) {
        return $this->set($key, $value);
    }

    public function offsetUnset ($key) {
        return $this->remove($key);
    }

    /* Implement Serializable */
    public function serialize () {
        return serialize($this->data);
    }

    public function unserialize ($data) {
        $this->values = deserialize($data);
    }

    /* Implement JsonSerializable */
    public function jsonSerialize () {
        return $this->values;
    }
}
