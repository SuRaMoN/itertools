<?php

namespace itertools;

use InvalidArgumentException;
use IteratorIterator;
use Traversable;
use ArrayIterator;


class FlippingIterator extends IteratorIterator
{
	public function __construct($iterator)
	{
		parent::__construct(IterUtil::asTraversable($iterator));
	}

	public function current()
	{
		return parent::key();
	}

	public function key()
	{
		return parent::current();
	}
}

