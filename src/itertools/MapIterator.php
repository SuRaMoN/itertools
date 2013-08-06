<?php

namespace itertools;

use Exception;
use IteratorIterator;
use Traversable;
use ArrayIterator;


class MapIterator extends IteratorIterator
{
	protected $callback;

	public function __construct($iterator, $callback)
	{
		parent::__construct(IterUtil::asTraversable($iterator));
		if (!is_callable($callback)) {
			throw new Exception('The callback must be callable');
		}
		$this->callback = $callback;
	}

	public function current()
	{
		return call_user_func($this->callback, parent::current());
	}
}

