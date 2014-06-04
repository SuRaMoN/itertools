<?php

namespace itertools;

use ArrayObject;
use PHPUnit_Framework_TestCase;


class InnerExposingIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testFunctionality()
	{
		$iterator = new InnerExposingIterator(new LookAheadIterator(new ArrayObject(array(1, 2, 3))));
		$this->assertEquals(3, $iterator->count());
	}
}

