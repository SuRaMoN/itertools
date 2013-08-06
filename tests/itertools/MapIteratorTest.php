<?php

namespace itertools;

use ArrayIterator;
use PHPUnit_Framework_TestCase;


class MapIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testMapIterator()
	{
		$square = function($n) { return $n * $n; };
		$this->assertEquals(array_map($square, array(1, 2, 3)), $this->imap(array(1, 2, 3), $square));
	}

	protected function imap($array, $callable)
	{
		return iterator_to_array(new MapIterator($array, $callable));
	}
}

