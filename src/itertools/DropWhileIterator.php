<?php

namespace itertools;

use InvalidArgumentException;
use FilterIterator;
use Iterator;


class DropWhileIterator extends FilterIterator
{
	protected $callback;
	protected $hasFailed;

	public function __construct($iterator, $callback)
	{
		parent::__construct(IterUtil::asIterator($iterator));
		if(!is_callable($callback)) {
			throw new InvalidArgumentException('No valid callback provided');
		}
		$this->callback = $callback;
		$this->hasFailed = false;
	}

	public function accept()
	{
		if($this->hasFailed) {
			return $this->hasFailed;
		}
		$this->hasFailed = ! call_user_func($this->callback, $this->current(), $this->key(), $this->getInnerIterator());
		return $this->hasFailed;
	}
}

