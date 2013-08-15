<?php

namespace itertools;

use ArrayAccess;
use SeekableIterator;


class ArrayAccessIterator implements SeekableIterator
{
	protected $array;
	protected $index;

	public function __construct(ArrayAccess $array, $startIndex = 0)
	{
		$this->array = $array;
		$this->seek($startIndex);
	}

	public function rewind()
	{
		$this->index = 0;
	}

	public function valid()
	{
		return $this->index < $this->array->count();
	}

	public function next()
	{
		$this->index += 1;
	}

	public function key()
	{
		return $this->index;
	}

	public function current()
	{
		return $this->array[$this->index];
	}

	public function seek($position)
	{
		$this->index = $position;
	}

}
