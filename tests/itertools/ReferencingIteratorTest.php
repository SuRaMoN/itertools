<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class ReferencingIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testBasicFunctionality()
	{
		$range = new RangeIterator(0, 10);
		$ref = new ReferencingIterator($range);
		$this->assertEquals(range(0, 10), iterator_to_array($ref));
	}

	/** @test */
	public function testSwitchingInnerIterator()
	{
		$range = new RangeIterator(0, 10);
		$ref = new ReferencingIterator($range);
		$ref->rewind();

		$this->assertSame($range, $ref->getInnerIterator());
		$this->assertEquals(0, $ref->current());

		$ref->setInnerIterator(new RangeIterator(5, 15));
		$ref->rewind();
		$this->assertEquals(5, $ref->current());
	}
}

