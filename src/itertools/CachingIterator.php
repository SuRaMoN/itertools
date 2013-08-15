<?php

namespace itertools;

use IteratorIterator;

/**
 * This iterator is able to prefetch its values and cache them.
 * This you to look ahead of the current iteration position.
 */
class CachingIterator extends IteratorIterator
{
	const NO_REWIND = 'NO_REWIND';
	const CACHE_ISSUED_REWIND = 'CACHE_ISSUED_REWIND';
	const OUTER_ISSUED_REWIND = 'OUTER_ISSUED_REWIND';

	protected $cache = array();
	protected $rewindStatus = self::NO_REWIND;

	public function __construct($innerIterator)
	{
		parent::__construct(IterUtil::asIterator($innerIterator));
	}

	protected function uncachedKey()
	{
		return $this->getInnerIterator()->key();
	}

	protected function uncachedCurrent()
	{
		return $this->getInnerIterator()->current();
	}

	protected function uncachedValid()
	{
		return $this->getInnerIterator()->valid();
	}

	protected function uncachedRewind()
	{
		return $this->getInnerIterator()->rewind();
	}

	protected function uncachedNext()
	{
		return $this->getInnerIterator()->next();
	}

	public function cacheUpTo($count)
	{
		if(self::NO_REWIND == $this->rewindStatus) {
			$this->uncachedRewind();
			$this->rewindStatus = self::CACHE_ISSUED_REWIND;
		}
		while(count($this->cache) < $count && $this->uncachedValid()) {
			$this->cache[] = (object) array('key' => $this->uncachedKey(), 'current' => $this->uncachedCurrent());
			$this->uncachedNext();
		}
	}

    public function rewind()
	{
		if(self::CACHE_ISSUED_REWIND == $this->rewindStatus) {
			$this->rewindStatus = self::OUTER_ISSUED_REWIND;
			return;
		}
		$this->cache = array();
		$this->uncachedRewind();
		$this->rewindStatus = self::OUTER_ISSUED_REWIND;
    }

    public function key()
	{
		$this->cacheUpTo(1);
        return reset($this->cache)->key;
    }

    public function current()
	{
		$this->cacheUpTo(1);
        return reset($this->cache)->current;
    }

    public function next()
	{
		array_shift($this->cache);
    }

	public function hasNext($offset = 1)
	{
		$this->cacheUpTo($offset);
		return count($this->cache) >= $offset;
	}

    public function valid()
	{
		$this->cacheUpTo(1);
		return count($this->cache) > 0;
    }
}

