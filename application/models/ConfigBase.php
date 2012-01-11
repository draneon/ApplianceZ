<?php

class Application_Model_ConfigBase {

    public function __construct($params = NULL) {
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->__set($key, $value);
                }
            }
        }
    }

	public function __set($key, $value) {
		$setter = 'set'.ucfirst($key);	
		$this->$setter($value);
	}

    public function __isset($key) {
        return method_exists($this, 'get'.ucfirst($key));
    }

	public function toArray() {
		return get_object_vars($this);
	}

    public function __toString() {
        $str = '';
        foreach ($this->toArray() as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $str .= $key . ' = '.$value."\n";
        }
        return $str;
    }

}
