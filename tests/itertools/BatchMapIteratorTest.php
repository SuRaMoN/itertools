<?php

namespace itertools;

use ArrayIterator;
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

	/** @test */
	public function testBatchMapIteratorMerge()
	{
		$input = new ArrayIterator(array(1, 1, 1, 1));
		$mappedInput = new BatchMapIterator(new BatchMapIterator($input, function($batch) {
			foreach($batch as & $a) { $a += 1; }
			return $batch;
		}, 3), function($batch) {
			foreach($batch as & $a) { $a *= 2; }
			return $batch;
		}, 3);
		$this->assertEquals(array(4, 4, 4, 4), iterator_to_array($mappedInput));
		$this->assertSame($input, $mappedInput->getInnerIterator());
	}

	/** @test */
	public function testSplitKeyValues()
	{
		$input = array(3 => 7, 4 => 8, 7 => 2);
		$mappedInput = new BatchMapIterator($input, function($pairs) {
			foreach($pairs as & $pair) {
				$pair = array($pair[1], $pair[0]);
			}
			return $pairs;
		}, 3, BatchMapIterator::SPLIT_KEY_VALUES);
		$this->assertEquals(array(7 => 3, 8 => 4, 2 => 7), iterator_to_array($mappedInput));
	}

	/** @test */
	public function testAddKeyList()
	{
		$input = array(3 => 7, 4 => 8, 7 => 2);
		$mappedInput = new BatchMapIterator($input, function($keys, $values) {
			return array($values, $keys);
		}, 3, BatchMapIterator::ADD_KEY_LIST);
		$this->assertEquals(array(7 => 3, 8 => 4, 2 => 7), iterator_to_array($mappedInput));
	}
}

