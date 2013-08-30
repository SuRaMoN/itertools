<?php

namespace itertools;

use SimpleXMLElement;
use IteratorIterator;
use PHPUnit_Framework_TestCase;
use ArrayIterator;


class IterUtilTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testAsIterator()
	{
		$this->assertTrue(IterUtil::asIterator(array()) instanceof ArrayIterator);
		$this->assertTrue(IterUtil::asIterator(new RangeIterator(1, 2)) instanceof RangeIterator);
		$this->assertTrue(IterUtil::asIterator(new Queue()) instanceof ArrayAccessIterator);
		$this->assertTrue(IterUtil::asIterator(new SimpleXMLElement('<root/>')) instanceof IteratorIterator);
	}

	/** @test */
	public function testAsTraversable()
	{
		$this->assertTrue(IterUtil::asTraversable(array()) instanceof ArrayIterator);
		$this->assertTrue(IterUtil::asTraversable(new RangeIterator(1, 2)) instanceof RangeIterator);
		$this->assertTrue(IterUtil::asTraversable(new Queue()) instanceof Queue);
		$this->assertTrue(IterUtil::asTraversable(new SimpleXMLElement('<root/>')) instanceof SimpleXMLElement);
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function testAsTraversableWithNonTraversableAsArgument()
	{
		IterUtil::asTraversable(1);
	}

	/** @test */
	public function testIsCollection()
	{
		$this->assertTrue(IterUtil::isCollection(array()));
		$this->assertTrue(IterUtil::isCollection(new RangeIterator(1, 2)));
		$this->assertTrue(IterUtil::isCollection(new Queue()));
		$this->assertTrue(IterUtil::isCollection(new SimpleXMLElement('<root/>')));

		$this->assertFalse(IterUtil::isCollection(1));
	}

	/** @test */
	public function testAssertIsCollectionForCollections()
	{
		IterUtil::assertIsCollection(array());
		IterUtil::assertIsCollection(new RangeIterator(1, 2));
		IterUtil::assertIsCollection(new Queue());
		IterUtil::assertIsCollection(new SimpleXMLElement('<root/>'));
	}

	/**
	 * @test
	 * @expectedException Exception
	 */
	public function testAssertIsCollectionForNonCollections()
	{
		IterUtil::assertIsCollection(1);
	}

	/** @test */
	public function testGetCurrentAndAdvanceForArray()
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
	public function testGetCurrentAndAdvanceForArrayShouldThrowExceptionOnEndOfRange()
	{
		$a = range(0, 1);
		next($a);
		$this->assertEquals(1, current($a));
		next($a);
		IterUtil::getCurrentAndAdvance($a);
	}

	/** @test */
	public function testGetCurrentAndAdvanceForArrayShouldNotThrowErrorWHenProvidedWithDefault()
	{
		$a = range(0, 1);
		next($a);
		$this->assertEquals(1, current($a));
		next($a);
		$c = IterUtil::getCurrentAndAdvance($a, array('default' => 'default-value'));
		$this->assertEquals($c, 'default-value');
	}

	/** @test */
	public function testGetCurrentAndAdvanceForIterator()
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
	public function testGetCurrentAndAdvanceForIteratorShouldThrowExceptionOnEndOfRange()
	{
		$a = new ArrayIterator(range(0, 1));
		$a->rewind();
		$a->next();
		$this->assertEquals(1, $a->current());
		$a->next();
		IterUtil::getCurrentAndAdvance($a);
	}

	/** @test */
	public function testGetCurrentAndAdvanceForIteratorShouldNotThrowErrorWHenProvidedWithDefault()
	{
		$a = new ArrayIterator(range(0, 1));
		$a->rewind();
		$a->next();
		$this->assertEquals(1, $a->current());
		$a->next();
		$c = IterUtil::getCurrentAndAdvance($a, array('default' => 'default-value'));
		$this->assertEquals($c, 'default-value');
	}

	/** @test */
	public function testResursiveIteratorToArray()
	{
		$iterator = new ArrayIterator(array(new ArrayIterator(range(0, 2)), new ArrayIterator(range(0, 2))));
		$expectedResult = array(range(0, 2), range(0, 2));
		$this->assertEquals($expectedResult, IterUtil::recursive_iterator_to_array($iterator));
	}
}

