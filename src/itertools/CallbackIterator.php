<?php

namespace itertools;

use Iterator;
use Traversable;


class CallbackIterator extends MapIterator {

	protected $currentValue;
	protected $currentValueUpToDate;
	protected $callback;

	public function __construct($callback) {
		$this->currentValueUpToDate = true;
		$this->callback = $callback;
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
        return true;
    }

}

