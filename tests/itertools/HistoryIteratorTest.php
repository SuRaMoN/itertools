<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class HistoryIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testHistoryRetrieval()
	{
		$it = new HistoryIterator(array(1, 2, 3, 4), 3);

		$it->rewind();
		$it->valid();
		$this->assertEquals(1, $it->current());
		$this->assertFalse($it->hasPrev());

		$it->next();
		$this->assertTrue($it->valid());
		$this->assertTrue($it->valid());
		$this->assertEquals(2, $it->current());
		$this->assertEquals(1, $it->prev(1));
		$this->assertTrue($it->hasPrev());

		$it->next();
		$this->assertTrue($it->valid());
		$this->assertEquals(3, $it->current());
		$this->assertEquals(2, $it->prev(1));
		$this->assertEquals(1, $it->prev(2));
	}

	/** @test */
	public function testNormalFunctionality()
	{
		$it = new HistoryIterator(array(1, 2, 3, 4));
		$this->assertEquals(4, count(iterator_to_array($it)));
	}
}

