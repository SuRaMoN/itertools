<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class BatchMapIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testBatchMapIterator()
	{
		$input = array(1, 1, 1, 1, 1, 1, 1);
		$mappedInput = new BatchMapIterator($input, function($elements) {
			foreach($elements as & $element) {
				$element += count($elements);
			}
			return $elements;
		}, 3);
		$this->assertEquals(array(4, 4, 4, 4, 4, 4, 2), iterator_to_array($mappedInput));
	}
}

