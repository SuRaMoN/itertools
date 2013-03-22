<?php

namespace itertools;

use Iterator;


class CachingIterator implements Iterator
{
	protected $cache = array();
	protected $inner;

	public function __construct($innerIterator)
	{
		$this->inner = IterUtil::asIterator($innerIterator);
	}

	public function cacheUpTo($count)
	{
		while(count($this->cache) < $count && $this->inner->valid()) {
			$this->cache[] = (object) array('key' => $this->inner->key(), 'current' => $this->inner->current());
			$this->inner->next();
		}
	}

    public function rewind()
	{
		$this->cache = array();
		return $this->inner->rewind();
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

