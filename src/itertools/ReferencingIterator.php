<?php

namespace itertools;

use OuterIterator;


class ReferencingIterator implements OuterIterator
{
	protected $innerIterator;

	public function __construct($innerIterator)
	{
		$this->innerIterator = IterUtil::asIterator($innerIterator);
	}

 	public function getInnerIterator()
 	{
 		return $this->innerIterator;
 	}
 
 	public function setInnerIterator($innerIterator)
 	{
 		$this->innerIterator = $innerIterator;
		return $this;
 	}

    public function rewind()
	{
		return $this->innerIterator->rewind();
    }

    public function current()
	{
		return $this->innerIterator->current();
    }

    public function key()
	{
		return $this->innerIterator->key();
    }

    public function next()
	{
		return $this->innerIterator->next();
    }

    public function valid()
	{
		return $this->innerIterator->valid();
    }
}
 
