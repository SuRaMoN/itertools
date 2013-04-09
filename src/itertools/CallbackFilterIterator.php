<?php

namespace itertools;

use FilterIterator;
use Iterator;
use Closure;


/** For PHP 5.3 */
class CallbackFilterIterator extends FilterIterator
{
	protected $callback;

	public function __construct(Iterator $iterator, Closure $callback = null)
	{
		parent::__construct($iterator);
		$this->callback = $callback;
	}

	public function accept()
	{
		return call_user_func($this->callback, $this->current(), $this->key(), $this->getInnerIterator());
	}
}

