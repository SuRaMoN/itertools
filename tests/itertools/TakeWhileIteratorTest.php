<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class TakeWhileIteratorTest extends PHPUnit_Framework_TestCase {

	/** @test */
	function testMainFunctionality() {
		$it = new TakeWhileIterator(range(0, 100), function($i) { return $i < 5; });
		$this->assertEquals(5, count(iterator_to_array($it)));
	}
}

