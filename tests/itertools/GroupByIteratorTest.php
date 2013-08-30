<?php

namespace itertools;

use ArrayIterator;
use PHPUnit_Framework_TestCase;


class GroupByIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testSimpleGroupByWithDefaultComparator()
	{
		$input = new GroupByIterator(array(0, 0, 1, 1, 2, 3));
		$expectedResult = array(
			array(0, 0),
			array(1, 1),
			array(2),
			array(3),
		);
		$this->assertEquals($expectedResult, IterUtil::recursive_iterator_to_array($input, false));
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidInput()
	{
		new GroupByIterator(array(), 'not-a-callable');
	}

	/** @test */
	public function testCustomComparator()
	{
		$input = new GroupByIterator(array(
			(object) array('name' => 'jos', 'age' => 3),
			(object) array('name' => 'mieke', 'age' => 3),
			(object) array('name' => 'tom', 'age' => 4),
			(object) array('name' => 'lotte', 'age' => 5),
			(object) array('name' => 'jaak', 'age' => 5),
		), function($v) { return $v->age; });
		$inputArray = IterUtil::recursive_iterator_to_array($input, false);

		$this->assertEquals('jos', $inputArray[0][0]->name);
		$this->assertEquals('mieke', $inputArray[0][1]->name);
		$this->assertEquals('tom', $inputArray[1][0]->name);
		$this->assertEquals('lotte', $inputArray[2][0]->name);
		$this->assertEquals('jaak', $inputArray[2][1]->name);
	}

	/** @test */
	public function testSkippingGroups()
	{
		$input = new GroupByIterator(array(0, 0, 1, 1, 2, 3));
		foreach($input as $i => $group) {
			switch($i) {
				case 1:
					$this->assertEquals(array(1, 1), iterator_to_array($group, false));
					break;

				case 3:
					$this->assertEquals(array(3), iterator_to_array($group, false));
					break;
			}
		}
	}
}

