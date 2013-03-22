<?php

namespace itertools;

use Iterator;


class CachingIterator implements Iterator
{
	const NO_REWIND = 'NO_REWIND', CACHE_ISSUED_REWIND = 'CACHE_ISSUED_REWIND', OUTER_ISSUED_REWIND = 'OUTER_ISSUED_REWIND';

	protected $cache = array();
	public $rewindStatus = self::NO_REWIND;
	protected $inner;

	public function __construct($innerIterator)
	{
		$this->inner = IterUtil::asIterator($innerIterator);
	}

	public function cacheUpTo($count)
	{
		if(self::NO_REWIND == $this->rewindStatus) {
			$this->inner->rewind();
			$this->rewindStatus = self::CACHE_ISSUED_REWIND;
		}
		while(count($this->cache) < $count && $this->inner->valid()) {
			$this->cache[] = (object) array('key' => $this->inner->key(), 'current' => $this->inner->current());
			$this->inner->next();
		}
	}

    public function rewind()
	{
		if(self::CACHE_ISSUED_REWIND == $this->rewindStatus) {
			$this->rewindStatus = self::OUTER_ISSUED_REWIND;
			return;
		}
		$this->cache = array();
		$this->inner->rewind();
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

	public function hasNext()
	{
		$this->cacheUpTo(1);
		return count($this->cache) >= 1;
	}

    public function valid()
	{
		$this->cacheUpTo(1);
		return count($this->cache) > 0;
    }
}

