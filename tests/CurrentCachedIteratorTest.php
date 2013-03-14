<?php

namespace itertools;

use PHPUnit_Framework_TestCase;
use CallbackFilterIterator;
use ArrayIterator;


class CurrentCachedIteratorTest extends PHPUnit_Framework_TestCase {

	/** @test */
	public function testMainFunctionality() {
		$count = 0;
		$it = new CurrentCachedIterator(new MapIterator(new ArrayIterator(range(0, 10)), function() use (&$count) { $count += 1; return null; }));

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
}

