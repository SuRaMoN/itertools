<?php

namespace itertools;

use Exception;
use PHPUnit_Framework_TestCase;


class ForkingIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testMainFunctionality()
	{
		foreach(new ForkingIterator(range(0, 20), array('maxChildren' => 3)) as $i)
		{
		}
	}

	/** @test
	 * @expectedException Exception
	 * @expectedExceptionMessage Child exited with non zero status
	 */
	public function testExceptionHandling()
	{
		foreach(new ForkingIterator(range(0, 20), array('maxChildren' => 3)) as $i)
		{
			exit(1);
		}
	}
}

