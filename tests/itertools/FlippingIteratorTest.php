<?php

namespace itertools;

use itertools\FlippingIterator;
use PHPUnit_Framework_TestCase;


class FlippingIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testFunctionality()
	{
		$list = array(1 => 2, 3 => 4, 5 => 6);
		$flippedList = iterator_to_array(new FlippingIterator($list));
		$this->assertEquals(array(2 => 1, 4 => 3, 6 => 5), $flippedList);
	}
}

