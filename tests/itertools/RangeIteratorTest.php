<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class RangeIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testRangeIteratorWithFiniteRange()
	{
		$this->assertEquals(range(1, 10), $this->irange(1, 10));
		$this->assertEquals(range(2, -10, -1), $this->irange(2, -10, -1));
	}

	/** @test */
	public function testRangeIteratorWithStepZero()
	{
		$range = new SliceIterator(new RangeIterator(1, 2, 0), 0, 20);
		$this->assertEquals(array_fill(0, 20, 1), iterator_to_array($range));
	}

	/** @test */
	public function testRangeIteratorWithInfiniteRange()
	{
		$infinteRange = new SliceIterator(new RangeIterator(1), 0, 10);
		$this->assertEquals(range(1, 10), iterator_to_array($infinteRange));
	}

	protected function irange($start, $end = null, $step = 1)
	{
		return iterator_to_array(new RangeIterator($start, $end, $step));
	}
}

