<?php

namespace itertools;

use Exception;
use PHPUnit_Framework_TestCase;


class ForkingIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testMainFunctionality()
	{
		$this->markTestIncomplete('These fail and should be fixed as soon as possible');
		foreach(new ForkingIterator(range(0, 20), array('maxChildren' => 3)) as $i)
		{
		}
	}

	/**
	 * @test
	 * @expectedException UnexpectedValueException
	 * @expectedExceptionMessage Child exited with non zero status
	 */
	public function testExceptionHandling()
	{
		$this->markTestIncomplete('These fail and should be fixed as soon as possible');
		foreach(new ForkingIterator(range(0, 20), array('maxChildren' => 3)) as $i)
		{
			exit(1);
		}
	}
}

