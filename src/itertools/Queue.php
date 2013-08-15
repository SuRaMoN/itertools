<?php

namespace itertools;

use Countable;
use ArrayAccess;
use IteratorAggregate;
use SplFixedArray;


/**
 * A queue implementation with random access with constant time complexity.
 * The SplQueue in the standard PHP library is implemented with a doubly linked
 * list. So it has linear time complexity to access elemenents by their index (
 * except for the bottom and top element). This queue implemntation addresses
 * this issue.
 *
 * This queue implementation has constant time complexity for:
 *  - push() / pop()
 *  - unshift() / shift()
 *  - offsetGet()
 * It has linear time complexity for:
 *  - offsetUnset()
 */
class Queue implements ArrayAccess, Countable, IteratorAggregate
{
	protected $data;
	protected $headIndex;
	protected $tailIndex;
	protected $size;

	public function __construct($collection = array())
	{
		$this->data = new SplFixedArray(1);
		$this->size = 0;
		$this->headIndex = 0;
		$this->tailIndex = 0;
		$this->pushAll($collection);
	}

	public function getIterator()
	{
		return new ArrayAccessIterator($this);
	}

	public function getMemoryUsageDataStructure()
	{
		return $this->data->getSize();
	}

	public function count()
	{
		return $this->size;
	}

	public function bottom()
	{
		return $this->offsetGet(0);
	}

	public function top()
	{
		return $this->offsetGet($this->count() - 1);
	}

	public function get($offset)
	{
		return $this->offsetGet($offset);
	}

	public function set($offset, $value)
	{
		return $this->offsetSet($offset, $value);
	}

	protected function doubleDataSize()
	{
		$newData = new SplFixedArray($this->data->getSize() * 2);
		foreach($this as $i => $element) {
			$newData[$i] = $element;
		}
		$this->headIndex = 0;
		$this->tailIndex = $this->size;
		$this->data = $newData;
	}

	protected function doubleDataSizeIfFull()
	{
		if($this->size == $this->data->count()) {
			$this->doubleDataSize();
		}
	}

	public function pushAll($collection)
	{
		IterUtil::assertIsCollection($collection);
		foreach($collection as $element) {
			$this->push($element);
		}
		return $this;
	}

	public function push($value)
	{
		$this->doubleDataSizeIfFull();
		$this->data[$this->tailIndex] = $value;
		$this->size += 1;
		$this->tailIndex = ($this->tailIndex + 1) % $this->data->count();
		return $this;
	}

	public function pop()
	{
		$oldValue = $this->offsetGet($this->size - 1);
		$this->tailIndex = ($this->tailIndex + $this->data->getSize() - 1) % $this->data->getSize();
		$this->size -= 1;
		return $oldValue;
	}

	public function unshiftAll($collection)
	{
		IterUtil::assertIsCollection($collection);
		foreach($collection as $element) {
			$this->unshift($element);
		}
		return $this;
	}

	public function asArray()
	{
		return iterator_to_array($this);
	}

	public function unshift($value)
	{
		$this->doubleDataSizeIfFull();
		$newHeadIndex = ($this->headIndex + $this->data->count() - 1) % $this->data->count();
		$this->data[$newHeadIndex] = $value;
		$this->size += 1;
		$this->headIndex = $newHeadIndex;
		return $this;
	}

	public function shift()
	{
		$oldValue = $this->offsetGet(0);
		$this->headIndex = ($this->headIndex + 1) % $this->data->count();
		$this->size -= 1;
		return $oldValue;
	}

	protected function assertOffsetExists($offset)
	{
		if($offset < 0 || $offset >= $this->size) {
			throw new OutOfBoundsException("Requested offset $offset for size {$this->size}");
		}
	}

	public function offsetExists($offset)
	{
		return 0 <= $offset && $offset < $this->size;
	}

	public function offsetGet($offset)
	{
		$this->assertOffsetExists($offset);
		return $this->data[($this->headIndex + $offset) % $this->data->count()];
	}

	public function offsetSet($offset, $value)
	{
		if($offset == $this->size) {
			return $this->push($value);
		}
		$this->assertOffsetExists($offset);
		$this->data[($this->headIndex + $offset) % $this->data->count()] = $value;
		return $this;
	}

	public function offsetUnset($offset)
	{
		$this->assertOffsetExists($offset);
		if(0 == $offset) {
			return $this->shift();
		} else if($offset == $this->size - 1) {
			return $this->pop();
		}
		$oldValue = $this->offsetGet($offset);
		$newData = new SplFixedArray($this->data->getSize());
		$i = 0;
		foreach($this as $oldIndex => $element) {
			if($oldIndex != $offset) {
				$newData[$i] = $element;
				$i += 1;
			}
		}
		$this->headIndex = 0;
		$this->tailIndex = $i;
		$this->size = $i;
		$this->data = $newData;
		return $oldValue;
	}
}

