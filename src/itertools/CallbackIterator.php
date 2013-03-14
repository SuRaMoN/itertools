<?php

namespace itertools;


class CallbackIterator extends CurrentCachedIterator {

	protected $callback;

	public function __construct($callback) {
		parent::__construct(new RepeatIterator(true));
		$this->callback = $callback;
	}

	public function uncachedCurrent() {
        return call_user_func($this->callback);
    }

	public function valid() {
		return true;
	}
}

