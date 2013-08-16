<?php

namespace itertools;

use InvalidArgumentException;
use FilterIterator;
use Iterator;


/** For PHP 5.3 */
class CallbackFilterIterator extends FilterIterator
{
	protected $callback;

	public function __construct($iterator, $callback)
	{
		parent::__construct(IterUtil::asIterator($iterator));
		if(!is_callable($callback)) {
			throw new InvalidArgumentException('No valid callback provided');
		}
		$this->callback = $callback;
	}

	public function accept()
	{
		return call_user_func($this->callback, $this->current(), $this->key(), $this->getInnerIterator());
	}
}

