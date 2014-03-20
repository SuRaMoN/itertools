<?php

namespace itertools;

use itertools\DateRangeIterator;
use PHPUnit_Framework_TestCase;


class DateRangeIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testSimpleRanges()
	{
		$this->assertCount(7, iterator_to_array(new DateRangeIterator('12 day ago', '+6 day', '+1 day')));
		$this->assertCount(2, iterator_to_array(new DateRangeIterator('2013-12-04', '2013-12-07', '+2 day')));
		$this->assertCount(2, iterator_to_array(new DateRangeIterator('2013-12-04', '2013-12-07', '+2 day', DateRangeIterator::EXCLUDE_RIGHT)));
		$this->assertCount(3, iterator_to_array(new DateRangeIterator('2013-12-04', '2013-12-07', '+1 day', DateRangeIterator::EXCLUDE_LEFT)));
		$this->assertCount(3, iterator_to_array(new DateRangeIterator('2013-12-04', '2013-12-07', '+1 day', DateRangeIterator::EXCLUDE_RIGHT)));
	}
}

