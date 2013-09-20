<?php

namespace itertools;

use Iterator;


class RepeatIterator implements Iterator
{
	protected $value;
	protected $key;

	public function __construct($value)
	{
		$this->value = $value;
		$this->key = 0;
	}

    public function rewind()
	{
    }

    public function current()
	{
        return $this->value;
    }

    public function key()
	{
		return $this->key;
    }

    public function next()
	{
		$this->key += 1;
    }

    public function valid()
	{
        return true;
    }	
}

