<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class CallbackIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testMainFunctionality()
	{
		$count = 0;
		$it = new CallbackIterator(function() use(&$count) { $count += 1; });

		$it->rewind();
		$it->valid();
		$it->current();
		$it->valid();
		$it->next();
		$it->current();
		$it->current();
		$it->current();
		$it->valid();
		$it->next();
		$it->current();

		$this->assertEquals(3, $count);
	}

	/** @test */
	public function testMaxInvocations()
	{
		$it = new CallbackIterator(function() { return 1; }, 3);
		$this->assertCount(3, iterator_to_array($it));
	}
}

