<?php

namespace itertools;


class CallbackIterator extends CurrentCachedIterator
{
	protected $maxInvocations;
	protected $callback;
	protected $invocationCount;

	public function __construct($callback, $maxInvocations = INF)
	{
		$this->maxInvocations = $maxInvocations;
		parent::__construct(new RepeatIterator(true));
		$this->callback = $callback;
	}

	public function rewind()
	{
		$this->invocationCount = 0;
		parent::rewind();
	}

	public function next()
	{
		$this->invocationCount += 1;
		parent::next();
	}

	public function uncachedCurrent()
	{
        return call_user_func($this->callback);
    }

	public function valid()
	{
		return $this->invocationCount < $this->maxInvocations;
	}
}

