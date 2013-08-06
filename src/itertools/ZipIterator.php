<?php

namespace itertools;

use Iterator;
use InvalidArgumentException;


class ZipIterator implements Iterator
{
	protected $iterators;
	protected $iterationCount;

	public function __construct(array $iterators)
	{
		if(count($iterators) == 0) {
			throw new InvalidArgumentException('Cannot construct a ZipIterator from an empty array of iterators');
		}
		$this->iterators = array();
		foreach($iterators as $iterator) {
			$this->iterators[] = IterUtil::asIterator($iterator);
		}
	}

	public static function newFromArguments()
	{
		$iterators = func_get_args();
		return new self($iterators);
	}

    public function rewind()
	{
		$this->iterationCount = 0;
		$rewindStatuses = array();
		foreach($this->iterators as $iterator) {
			$rewindStatuses[] = $iterator->rewind();
		}
		return $rewindStatuses;
    }

    public function key()
	{
		return $this->iterationCount++;
    }

    public function current()
	{
		$currentValues = array();
		foreach($this->iterators as $iterator) {
			$currentValues[] = $iterator->current();
		}
		return $currentValues;
    }

    public function next()
	{
		$nextStatuses = array();
		foreach($this->iterators as $iterator) {
			$nextStatuses[] = $iterator->next();
		}
		return $nextStatuses;
    }

    public function valid()
	{
		$valid = true;
		foreach($this->iterators as $iterator) {
			$valid = $iterator->valid() && $valid;
		}
		return $valid;
    }
}

