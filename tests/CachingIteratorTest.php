<?php

namespace itertools;

use ArrayIterator;
use CallbackFilterIterator;
use EmptyIterator;
use PHPUnit_Framework_TestCase;


class CachingIteratorTest extends PHPUnit_Framework_TestCase {

	/** @test */
	public function testMainFunctionality() {
		$count = 0;
		$it = new CachingIterator(new MapIterator(new ArrayIterator(range(0, 10)), function($i) use (&$count) { $count += 1; return $i; }));

		$it->rewind();
		$it->valid();
		$this->assertEquals(0, $it->current());

		$it->valid();
		$it->next();
		$this->assertEquals(1, $it->current());
		$this->assertEquals(1, $it->current());
		$this->assertEquals(1, $it->current());

		$it->valid();
		$it->next();
		$this->assertEquals(2, $it->current());

		$this->assertEquals(3, $count);
	}

	/** @test */
	public function testValidMethod() {
		foreach(new CachingIterator(new EmptyIterator()) as $v) {
			$this->assertTrue(false, 'should not be here');
		}
	}
}

