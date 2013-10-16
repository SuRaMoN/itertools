<?php

namespace itertools;

use ArrayIterator;
use EmptyIterator;
use OuterIterator;
use Traversable;


class ChainIterator implements OuterIterator
{
	const DONT_USE_KEYS = 'DONT_USE_KEYS';
	const USE_KEYS = 'USE_KEYS';

	protected $useOriginalKeys;
	protected $iterator;
	protected $currentSubIterator;
	protected $count;

	public function __construct($iterator, $useOriginalKeys = self::USE_KEYS)
	{
		$this->useOriginalKeys = $useOriginalKeys;
		$this->iterator = IterUtil::asIterator($iterator);
		$this->currentSubIterator = new EmptyIterator();
	}

	public function getInnerIterator()
	{
		return $this->iterator;
	}

	public function setNextValidSubIterator()
	{
		while($this->iterator->valid()) {
			$this->currentSubIterator = IterUtil::asIterator($this->iterator->current());
			$this->currentSubIterator->rewind();
			if($this->currentSubIterator->valid()) {
				return;
			}
			$this->iterator->next();
		}
		$this->currentSubIterator = new EmptyIterator();
	}

    public function rewind()
	{
		$this->iterator->rewind();
		$this->setNextValidSubIterator();
		$this->count = 0;
    }

    public function current()
	{
        return $this->currentSubIterator->current();
    }

    public function key()
	{
		if($this->useOriginalKeys == self::DONT_USE_KEYS) {
			return $this->count;
		} else {
			return $this->currentSubIterator->key();
		}
    }

    public function next()
	{
		$this->currentSubIterator->next();
		if(! $this->currentSubIterator->valid()) {
			$this->iterator->next();
			$this->setNextValidSubIterator();
		}
		$this->count += 1;
    }

    public function valid()
	{
        return $this->currentSubIterator->valid();
    }
}

