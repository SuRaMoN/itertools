<?php

namespace itertools;

use EmptyIterator;
use ArrayIterator;
use PHPUnit_Framework_TestCase;


class ChainIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testBasicFunctionality()
	{
		$it = new ChainIterator(new ArrayIterator(array(
			new RangeIterator(0, 10),
			new EmptyIterator(),
			new RangeIterator(11, 20),
		)));
		$this->assertEquals(range(0, 20), iterator_to_array($it, false));
		$this->assertTrue($it->getInnerIterator() instanceof ArrayIterator);
	}

	/** @test */
	public function testUseKeys()
	{
		$it = new ChainIterator(new ArrayIterator(array(
			new RangeIterator(0, 10),
			new RangeIterator(11, 20),
		)));
		$this->assertEquals(11, count(iterator_to_array($it)));
	}

	/** @test */
	public function testDontUseKeys()
	{
		$it = new ChainIterator(new ArrayIterator(array(
			new RangeIterator(0, 10),
			new RangeIterator(11, 20),
		)), ChainIterator::DONT_USE_KEYS);
		$this->assertEquals(range(0, 20), iterator_to_array($it));
	}
}

