<?php

namespace itertools;

use Iterator;


class TakeWhileIterator extends CurrentCachedIterator {

	protected $filter;

	public function __construct($inner, $filter) {
		parent::__construct($inner);
		$this->filter = $filter;
	}

	public function uncachedValid() {
		if(!parent::uncachedValid()) {
			return false;
		}
        return call_user_func($this->filter, $this->current());
    }
}

