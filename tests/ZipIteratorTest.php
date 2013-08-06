<?php

namespace itertools;

use ArrayIterator;
use PHPUnit_Framework_TestCase;


class ZipIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testZipIterator()
	{
		$column1 = new ArrayIterator(array(1, 2, 3, 4));
		$column2 = new ArrayIterator(array('a', 'b', 'c'));
		$column3 = new ArrayIterator(array('A', 'B', 'C'));

		$zipIterator = ZipIterator::newFromArguments($column1, $column2, $column3);

		$zippedValues = iterator_to_array($zipIterator);
		$this->assertEquals(array(1, 'a', 'A'), $zippedValues[0]);
		$this->assertEquals(array(2, 'b', 'B'), $zippedValues[1]);
		$this->assertEquals(array(3, 'c', 'C'), $zippedValues[2]);
		$this->assertEquals(3, count($zippedValues));
	}
}

 
