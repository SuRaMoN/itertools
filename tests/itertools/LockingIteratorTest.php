<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class LockingIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testWithoutLockerNameMapper()
	{
		$it = new RangeIterator(0, 10);
		$sum = 0;
		foreach(new LockingIterator($it, sys_get_temp_dir()) as $v) {
			$sum += $v;
		}
		$this->assertEquals(55, $sum);
	}

	/** @test */
	public function testWithLockerNameMapper()
	{
		$it = new RangeIterator(0, 10);
		$sum = 0;
		foreach(new LockingIterator($it, sys_get_temp_dir(), function($v) { return 1; }) as $v) {
			$sum += $v;
		}
		$this->assertEquals(55, $sum);
	}
}

