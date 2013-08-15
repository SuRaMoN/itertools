<?php

namespace itertools;

use InvalidArgumentException;
use IteratorIterator;
use Traversable;
use ArrayIterator;


/**
 * Iterator equivalent or [array_map](http://be1.php.net/manual/en/function.array-map.php).
 * Example:
 *     $positiveNumbers = new RangeIterator(0, INF); // all numbers from 0 to infinity
 *     $positiveSquareNumbers = new MapIterator($positiveNumbers, function($n) {return $n*$n;}); // all positive square numbers
 */
class MapIterator extends IteratorIterator
{
	protected $callback;

	public function __construct($iterator, $callback)
	{
		parent::__construct(IterUtil::asTraversable($iterator));
		if (!is_callable($callback)) {
			throw new InvalidArgumentException('The callback must be callable');
		}
		$this->callback = $callback;
	}

	public function current()
	{
		return call_user_func($this->callback, parent::current());
	}
}

