<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class ComposingIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testBasicFunctionality()
	{
		$values = ComposingIterator::newInstance()
			->range(0, 10)
			->filter(function($v) { return 10 != $v; })
			->groupBy(function($v) { return 5 < $v; })
			->chain()
			->slice(1)
			->map(function($v) { return (int) ($v / 2); })
			->unique()
			->takeWhile(function($v) { return 4 > $v; })
			->toArray(ComposingIterator::DONT_USE_KEYS);
		$this->assertEquals(range(0, 3), $values);

		$values = ComposingIterator::newInstance()
			->source(new RepeatIterator(1))
			->cacheCurrent()
			->chunk(20)
			->map(function($batch) { return array_slice($batch, 0, 10); })
			->slice(0, 10)
			->chain()
			->zipWith(new RepeatIterator(2))
			->zipWithAll(array(new RepeatIterator(3), new RepeatIterator(4)))
			->takeWhile(function($v) { return 4 == $v[2]; })
			->count();
		$this->assertEquals(100, $values);

		$count = ComposingIterator::newInstance()
			->source(array(1, 2, 3, 4))
			->skipFirst()
			->count();
		$this->assertEquals(3, $count);

		$values = ComposingIterator::newInstance()
			->source(array(
				'jos   , 5',
				'piet  , 7',
			))
			->fixedLengthFormattedStringFromTemplate('name  , age')
			->toArray();
		$this->assertEquals('jos   ', $values[0]['name']);
		$this->assertEquals('5', $values[0]['age']);
	}

	/**
	 * @test
	 * @expectedException BadMethodCallException
	 */
	public function testCallingUnknownMethods()
	{
		ComposingIterator::newInstance()->bliablabloe();
	}
}

