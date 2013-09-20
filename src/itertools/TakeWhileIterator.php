<?php

namespace itertools;

use IteratorIterator;


class TakeWhileIterator extends IteratorIterator
{
	protected $filter;

	public function __construct($iterable, $filter)
	{
		parent::__construct(IterUtil::asTraversable($iterable));
		$this->filter = $filter;
	}

	public function valid()
	{
		if(!parent::valid()) {
			return false;
		}
        return call_user_func($this->filter, $this->current(), $this->key());
    }
}

