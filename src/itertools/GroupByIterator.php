<?php

namespace itertools;

use NoRewindIterator;
use InvalidArgumentException;
use IteratorIterator;


class GroupByIterator extends IteratorIterator
{
	protected $comparator;
	protected $currentGroupIterator;
	protected $groupIndex;

	public function __construct($innerIterable, $comparator = null)
	{
		parent::__construct(IterUtil::asIterator($innerIterable));
		if(null === $comparator) {
			$comparator = function($value) { return $value; };
		}
		if(!is_callable($comparator)) {
			throw new InvalidArgumentException('Comparator must be a callable');
		}
		$this->comparator = $comparator;
		$this->groupIndex = 0;
	}

	public function key()
	{
		return $this->groupIndex;
	}

	protected function gotoNextGroup()
	{
		$currentGroupIterator = $this->current();
		while($currentGroupIterator->valid()) {
			$currentGroupIterator->next();
		}
	}

	public function valid()
	{
		return $this->getInnerIterator()->valid();
	}

	public function current()
	{
		if(null !== $this->currentGroupIterator) {
			return $this->currentGroupIterator;
		}
		$comparator = $this->comparator;
		$currentComparatorResult = call_user_func($this->comparator, $this->getInnerIterator()->current());
		$this->currentGroupIterator = new TakeWhileIterator(new NoRewindIterator($this->getInnerIterator()), function($value) use ($comparator, $currentComparatorResult) {
			return call_user_func($comparator, $value) == $currentComparatorResult;
		});
		$this->currentGroupIterator->rewind();
		return $this->currentGroupIterator;
	}

	public function next()
	{
		$this->gotoNextGroup();
		$this->currentGroupIterator = null;
		$this->groupIndex += 1;
	}

	public function rewind()
	{
		$this->currentGroupIterator = null;
		$this->groupIndex = 0;
		parent::rewind();
	}
}

