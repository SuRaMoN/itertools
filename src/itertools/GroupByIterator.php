<?php

namespace itertools;

use NoRewindIterator;
use InvalidArgumentException;
use IteratorIterator;


class GroupByIterator extends IteratorIterator
{
	protected $comparator;

	public function __construct($innerIterable, $comparator = null)
	{
		if(null === $comparator) {
			$comparator = function($value) { return $value; };
		}
		if(!is_callable($comparator)) {
			throw new InvalidArgumentException('Comparator must be a callable');
		}
		$this->comparator = $comparator;
		parent::__construct(IterUtil::asIterator($innerIterable));
	}

	public function key()
	{
		return $this->getInnerIterator()->key();
	}

	public function valid()
	{
		return $this->getInnerIterator()->valid();
	}

	public function current()
	{
		$comparator = $this->comparator;
		$currentComparatorResult = call_user_func($this->comparator, $this->getInnerIterator()->current());
		return new TakeWhileIterator(new NoRewindIterator($this->getInnerIterator()), function($value) use ($comparator, $currentComparatorResult) {
			return call_user_func($comparator, $value) == $currentComparatorResult;
		});
	}

	public function next()
	{
	}
}

