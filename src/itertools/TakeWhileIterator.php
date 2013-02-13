<?php

namespace itertools;

use IteratorIterator;


class TakeWhileIterator extends IteratorIterator {

	protected $currentValue;
	protected $currentValueUpToDate;
	protected $filter;

	public function __construct($filter) {
		$this->currentValueUpToDate = true;
		$this->filter = $filter;
	}

	protected function updateCurrentValue() {
		if(!$this->currentValueUpToDate) {
			$this->currentValue = call_user_func($this->filter);
			$this->currentValueUpToDate = true;
		}
	}

    function rewind() {
    }

    function current() {
		$this->updateCurrentValue();
        return $this->currentValue;
    }

    function key() {
		return null;
    }

    function next() {
		$this->currentValueUpToDate = false;
    }

    function valid() {
		$this->updateCurrentValue();
        return call_user_func($this->filter, $this->currentValue);
    }
}

