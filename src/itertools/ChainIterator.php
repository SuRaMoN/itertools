<?php

namespace itertools;

use Iterator;
use Traversable;
use EmptyIterator;
use ArrayIterator;


class ChainIterator implements Iterator
{
	public $iterator;
	public $currentSubIterator;

	public function __construct($iterator)
	{
		$this->iterator = IterUtil::asIterator($iterator);
		$this->currentSubIterator = new EmptyIterator();
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
    }

    public function current()
	{
        return $this->currentSubIterator->current();
    }

    public function key()
	{
        return $this->currentSubIterator->key();
    }

    public function next()
	{
		$this->currentSubIterator->next();
		if(!$this->currentSubIterator->valid()) {
			$this->iterator->next();
			$this->setNextValidSubIterator();
		}
    }

    public function valid()
	{
        return $this->currentSubIterator->valid();
    }
}

