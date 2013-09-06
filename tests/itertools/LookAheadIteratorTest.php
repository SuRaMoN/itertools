<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class LookAheadIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testBasicIteration()
	{
		$it = new LookAheadIterator(range(0, 10));
		$this->assertEquals(range(0, 10), iterator_to_array($it), 'The LookAheadIterator should not modify the inner iterator');
	}

	/** @test */
	public function testSimpleLookAheads()
	{
		$it = new LookAheadIterator(range(0, 10));
		$i = 0;
		foreach($it as $key => $value) {
			$this->assertEquals($i, $value);
			if($key < 10) {
				$this->assertEquals($it->getNext(), $i + 1);
			}
			$this->assertEquals($i, $value);
			$this->assertEquals($i, $key);
			$i += 1;
		}
	}

	/** @test */
	public function testLookAheadWithoutRewind()
	{
		$it = new LookAheadIterator(range(0, 10));
		$this->assertEquals(10, $it->getNext(10));
		$this->assertEquals(range(0, 10), iterator_to_array($it));
	}

	/** @test */
	public function testKeyLookAhead()
	{
		$it = new LookAheadIterator(range(1, 10));
		$this->assertEquals(9, $it->getNextKey(9));
		$this->assertEquals(10, $it->getNext(9));
	}

	/**
	 * @test
	 * @expectedException OutOfBoundsException
	 */
	public function testOutOfBounds()
	{
		$it = new LookAheadIterator(array());
		$it->getNext();
	}

	/** @test */
	public function testHasNext()
	{
		$it = new LookAheadIterator(range(0, 10));
		$i = 0;
		foreach($it as $e) {
			$this->assertEquals($it->hasNext(), $e < 10);
			$i += 1;
		}
	}
}

