<?php

namespace itertools;

use Exception;
use IteratorIterator;
use Traversable;


class MapIterator extends IteratorIterator {

	protected $callback;

	public function __construct(Traversable $iterator, $callback) {
		parent::__construct($iterator);
		if (!is_callable($callback)) {
			throw new Exception('The callback must be callable');
		}
		$this->callback = $callback;
	}

	public function current() {
		return call_user_func($this->callback, parent::current());
	}
}

