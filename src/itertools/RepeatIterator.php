<?php

namespace itertools;

use Iterator;


class RepeatIterator implements Iterator {

	protected $value;
	protected $key;

	public function __construct($value) {
		$this->value = $value;
		$this->key = 0;
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
		$this->key += 1;
    }

    function valid() {
        return true;
    }	
}

