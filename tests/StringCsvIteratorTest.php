<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class StringCsvIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testStringCsvIteratorWithHeader()
	{
		$it = new StringCsvIterator(<<<EOF
"col1", "col2"
"a11", "a12"
"a21", "a22"
EOF
		);

		$data = iterator_to_array($it);
		$this->assertEquals(2, count($data));
		$this->assertEquals('a22', $data[1]['col2']);
	}
}

