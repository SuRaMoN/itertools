<?php

namespace itertools;

use EmptyIterator;
use PHPUnit_Framework_TestCase;
use ArrayIterator;


class UniqueIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testFilterWithStrictComparisation()
	{
		$it = new ArrayIterator(array('1', 1, 1, 1, '1', '1'));

		$data = iterator_to_array(new UniqueIterator($it, array('compareType' => UniqueIterator::COMPARE_STRICT)));
		$this->assertEquals(array('1', 1, '1'), array_values($data));
	}

	/** @test */
	public function testFilterWithNonStrictComparisation()
	{
		$it = new ArrayIterator(array('1', 1, 1, 1, '1', 1, 2, '2', 2, '2'));

		$data = iterator_to_array(new UniqueIterator($it, array('compareType' => UniqueIterator::COMPARE_NONSTRICT)));
		$this->assertEquals(array('1', 2), array_values($data));
	}

	/**
	 * @test
	 * @expectedException \InvalidArgumentException
	 */
	public function testShouldWarningIfInvalidOptionsAreSpecified()
	{
		new UniqueIterator(new EmptyIterator(), array('unkownOptions' => 'value'));
	}
}

