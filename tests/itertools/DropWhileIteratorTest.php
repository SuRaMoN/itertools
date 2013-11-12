<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class DropWhileIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testMainFunctionality()
	{
		$i = new DropWhileIterator(array(1, 2, 3, 2, 1), function($v) { return $v < 3;});
		$this->assertEquals(array(3, 2, 1), iterator_to_array($i, false));
	}
}

