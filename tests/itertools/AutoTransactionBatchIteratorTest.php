<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class AutoTransactionBatchIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testBasicFunctionality()
	{
		$beginTransactionCount = 0;
		$commitCount = 0;
		$pdo = new MockPDO(array(
			'beginTransaction' => function() use (&$beginTransactionCount) { $beginTransactionCount += 1; },
			'commit' => function() use (&$commitCount) { $commitCount += 1; },
		));

		$iterator = new AutoTransactionBatchIterator(range(0, 4), $pdo, 2);

		$iterator->rewind();
		$this->assertEquals(array(0, 0), array($beginTransactionCount, $commitCount));
		$this->assertTrue($iterator->valid());
		$this->assertEquals(array(1, 0), array($beginTransactionCount, $commitCount));
		// get first element now

		$iterator->next();
		$this->assertTrue($iterator->valid());
		$this->assertEquals(array(1, 0), array($beginTransactionCount, $commitCount));
		// get second element now

		$iterator->next();
		$this->assertTrue($iterator->valid());
		$this->assertEquals(array(2, 1), array($beginTransactionCount, $commitCount));
		// get third element now

		$iterator->next();
		$this->assertTrue($iterator->valid());
		$this->assertEquals(array(2, 1), array($beginTransactionCount, $commitCount));
		// get forth element now

		$iterator->next();
		$this->assertTrue($iterator->valid());
		$this->assertEquals(array(3, 2), array($beginTransactionCount, $commitCount));
		// get fith element now

		$iterator->next();
		$this->assertFalse($iterator->valid());
		$this->assertEquals(array(3, 3), array($beginTransactionCount, $commitCount));
		// end of iteration
	}

	/** @test */
	public function testEmptyIterator()
	{
		$beginTransactionCount = 0;
		$commitCount = 0;
		$pdo = new MockPDO(array(
			'beginTransaction' => function() use (&$beginTransactionCount) { $beginTransactionCount += 1; },
			'commit' => function() use (&$commitCount) { $commitCount += 1; },
		));

		$iterator = new AutoTransactionBatchIterator(array(), $pdo, 2);
		foreach($iterator as $i) {
		}
		$this->assertEquals(array(0, 0), array($beginTransactionCount, $commitCount));
	}

	/** @test */
	public function testForeach()
	{
		$beginTransactionCount = 0;
		$commitCount = 0;
		$pdo = new MockPDO(array(
			'beginTransaction' => function() use (&$beginTransactionCount) { $beginTransactionCount += 1; },
			'commit' => function() use (&$commitCount) { $commitCount += 1; },
		));

		$iterator = new AutoTransactionBatchIterator(range(0, 4), $pdo, 2);
		foreach($iterator as $i => $element) {
			switch($i) {
				case 0:
					$this->assertEquals(array(1, 0), array($beginTransactionCount, $commitCount)); break;
				case 1:
					$this->assertEquals(array(1, 0), array($beginTransactionCount, $commitCount)); break;
				case 2:
					$this->assertEquals(array(2, 1), array($beginTransactionCount, $commitCount)); break;
				case 3:
					$this->assertEquals(array(2, 1), array($beginTransactionCount, $commitCount)); break;
				case 4:
					$this->assertEquals(array(3, 2), array($beginTransactionCount, $commitCount)); break;
			}
		}
		$this->assertEquals(array(3, 3), array($beginTransactionCount, $commitCount));
	}
}
