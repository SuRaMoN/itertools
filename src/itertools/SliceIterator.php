<?php

namespace itertools;

use IteratorIterator;


class SliceIterator extends IteratorIterator
{
	const PRESERVE_KEYS = true;
	const DONT_PRESERVE_KEYS = false;

	protected $offset;
	protected $length;
	protected $preserveKeys;
	protected $innerIterationCount;
	protected $outerIterationCount;

	public function __construct($iterable, $offset, $length = INF, $preserveKeys = false)
	{
		$this->offset = $offset;
		$this->length = $length;
		$this->preserveKeys = $preserveKeys;
		parent::__construct(IterUtil::asTraversable($iterable));
	}

	public function key()
	{
		if($this->preserveKeys) {
			return parent::key();
		} else {
			return $this->outerIterationCount;
		}
	}

	public function next()
	{
		$this->innerIterationCount += 1;
		$this->outerIterationCount += 1;
		parent::next();
	}

	public function valid()
	{
		while($this->innerIterationCount < $this->offset) {
			if(!parent::valid()) {
				return false;
			}
			$this->innerIterationCount += 1;
			parent::next();
		}
		if($this->innerIterationCount >= $this->offset + $this->length) {
			return false;
		}
		return parent::valid();
	}

	public function rewind()
	{
		$this->innerIterationCount = 0;
		$this->outerIterationCount = 0;
		parent::rewind();
	}
}

