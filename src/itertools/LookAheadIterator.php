<?php

namespace itertools;

use IteratorIterator;


class LookAheadIterator extends IteratorIterator
{
	protected $lookAheads;
	protected $skipAutoRewind;
	protected $skipNextNormalRewind;

	public function __construct($innerIterator)
	{
		parent::__construct(IterUtil::asIterator($innerIterator));
		$this->lookAheads = new Queue();
		$this->skipAutoRewind = false;
		$this->skipNextNormalRewind = false;
	}

	public function next()
	{
		if($this->lookAheads->isEmpty()) {
			return parent::next();
		}
		$this->lookAheads->shift();
	}

	public function valid()
	{
		if(! $this->lookAheads->isEmpty()) {
			return true;
		}
		return parent::valid();
	}

	public function key()
	{
		if(! $this->lookAheads->isEmpty()) {
			return $this->lookAheads->bottom()->key;
		}
		return parent::current();
	}

	public function current()
	{
		if(! $this->lookAheads->isEmpty()) {
			return $this->lookAheads->bottom()->value;
		}
		return parent::current();
	}

	public function prefetchUpTo($prefetchCount)
	{
		$this->autoRewind();
		$prefetchCount = max(0, $prefetchCount - $this->lookAheads->count());
		for($i = 0; $i < $prefetchCount; $i += 1) {
			if(! parent::valid()) {
				return;
			}
			$this->lookAheads->push((object) array('value' => parent::current(), 'key' => parent::key()));
			parent::next();
		}
	}

	public function getNext($n = 1)
	{
		$this->prefetchUpTo($n + 1);
		return $this->lookAheads->offsetGet($n)->value;
	}

	public function getNextKey($n = 1)
	{
		$this->prefetchUpTo($n + 1);
		return $this->lookAheads->offsetGet($n)->key;
	}

	public function hasNext($n = 1)
	{
		$this->prefetchUpTo($n + 1);
		return $this->lookAheads->count() > $n;
	}

	public function rewind()
	{
		if($this->skipNextNormalRewind) {
			$this->skipNextNormalRewind = false;
			return;
		}
		parent::rewind();
		$this->skipAutoRewind = true;
	}

	protected function autoRewind()
	{
		if(! $this->skipAutoRewind) {
			parent::rewind();
			$this->skipNextNormalRewind = true;
		}
	}
}

