<?php

namespace itertools;

use Iterator;


class RepeatIterator implements Iterator {

	protected $value;
	protected $key;

	public function __construct($value, $key = null) {
		$this->value = $value;
		$this->key = $key;
	}

    function rewind() {
    }

    function current() {
        return $this->value;
    }

    function key() {
		return $this->key;
    }

    function next() {
    }

    function valid() {
        return true;
    }	
}

