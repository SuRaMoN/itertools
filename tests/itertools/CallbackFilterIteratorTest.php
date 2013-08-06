<?php

namespace itertools;

use ArrayIterator;
use PHPUnit_Framework_TestCase;


class CallbackFilterIteratorTest extends PHPUnit_Framework_TestCase {

	/** @test */
	public function testMainFunctionality() {
		$it = new ArrayIterator(array(1, 2, 3, 4, 5));
		$it = new CallbackFilterIterator($it, function($v) { return $v != 2; });
		$this->assertEquals(array(1, 3, 4, 5), array_values(iterator_to_array($it)));
	}
}

