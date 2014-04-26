<?php

namespace itertools;

use itertools\ProcessCsvIterator;
use PHPUnit_Framework_TestCase;


class ProcessCsvIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testFunctionality()
	{
		$i = new ProcessCsvIterator('echo "a,b,c"', array('hasHeader' => false));
		$data = iterator_to_array($i);
		$this->assertEquals(array('a', 'b', 'c'), $data[0]);
	}
}

