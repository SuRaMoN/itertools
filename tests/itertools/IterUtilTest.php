<?php

namespace itertools;

use PHPUnit_Framework_TestCase;
use ArrayIterator;


class IterUtilTest extends PHPUnit_Framework_TestCase
{

	/** @test */
	function testGetCurrentAndAdvanceForArray()
	{
		$a = range(0, 5);
		next($a);
		$this->assertEquals(1, current($a));
		$c = IterUtil::getCurrentAndAdvance($a);
		$this->assertEquals(1, $c);
		$this->assertEquals(2, current($a));
	}

	/**
	 * @test
	 * @expectedException Exception
	 * @expectedExceptionMessage Error while advancing iterable
	 **/
	function testGetCurrentAndAdvanceForArrayShouldThrowExceptionOnEndOfRange()
	{
		$a = range(0, 1);
		next($a);
		$this->assertEquals(1, current($a));
		next($a);
		$c = IterUtil::getCurrentAndAdvance($a);
	}

	/** @test */
	function testGetCurrentAndAdvanceForArrayShouldNotThrowErrorWHenProvidedWithDefault()
	{
		$a = range(0, 1);
		next($a);
		$this->assertEquals(1, current($a));
		next($a);
		$c = IterUtil::getCurrentAndAdvance($a, array('default' => 'default-value'));
		$this->assertEquals($c, 'default-value');
	}

	/** @test */
	function testGetCurrentAndAdvanceForIterator()
	{
		$a = new ArrayIterator(range(0, 5));
		$a->rewind();
		$a->next();
		$this->assertEquals(1, $a->current());
		$c = IterUtil::getCurrentAndAdvance($a);
		$this->assertEquals(1, $c);
		$this->assertEquals(2, $a->current());
	}

	/**
	 * @test
	 * @expectedException Exception
	 * @expectedExceptionMessage Error while advancing iterable
	 **/
	function testGetCurrentAndAdvanceForIteratorShouldThrowExceptionOnEndOfRange()
	{
		$a = new ArrayIterator(range(0, 1));
		$a->rewind();
		$a->next();
		$this->assertEquals(1, $a->current());
		$a->next();
		$c = IterUtil::getCurrentAndAdvance($a);
	}

	/** @test */
	function testGetCurrentAndAdvanceForIteratorShouldNotThrowErrorWHenProvidedWithDefault()
	{
		$a = new ArrayIterator(range(0, 1));
		$a->rewind();
		$a->next();
		$this->assertEquals(1, $a->current());
		$a->next();
		$c = IterUtil::getCurrentAndAdvance($a, array('default' => 'default-value'));
		$this->assertEquals($c, 'default-value');
	}

}

