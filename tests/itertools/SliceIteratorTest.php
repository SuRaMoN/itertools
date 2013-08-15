<?php

namespace itertools;

use ArrayIterator;
use PHPUnit_Framework_TestCase;


class SliceIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testSliceIterator()
	{
		$a = range(5, 20);
		$this->assertEquals(array_slice($a, 10), $this->islice($a, 10));
		$this->assertEquals(array_slice($a, 30), $this->islice($a, 30));
		$this->assertEquals(array_slice($a, 1, 5), $this->islice($a, 1, 5));
		$this->assertEquals(array_slice($a, 1, 200), $this->islice($a, 1, 200));
		$this->assertEquals(array_slice($a, 1, 200, true), $this->islice($a, 1, 200, true));
	}

	protected function islice($array, $offset, $length = INF, $preserve_keys = false)
	{
		return iterator_to_array(new SliceIterator($array, $offset, $length, $preserve_keys));
	}
}

