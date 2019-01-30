<?php

namespace itertools;

use PHPUnit_Framework_TestCase;

class StringCsvIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testStringCsvIteratorWithHeaderInFirstLine()
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

	/** @test */
	public function testStringCsvIteratorWithHeaderSpecified()
	{
		$it = new StringCsvIterator(<<<EOF
"a11", "a12"
"a21", "a22"
EOF
		, array('header' => array('col1', 'col2')));

		$data = iterator_to_array($it);
		$this->assertEquals(2, count($data));
		$this->assertEquals('a22', $data[1]['col2']);
	}

	/**
	 * @test
	 * @expectedException itertools\InvalidCsvException
	 */
	public function testStringCsvWithMissingRowsException()
	{
		$it = new StringCsvIterator(<<<EOF
"col1", "col2"
"a21"
EOF
		);

		$data = iterator_to_array($it);
	}

	/** @test */
	public function testStringCsvWithMissingRowsIgnored()
	{
		$it = new StringCsvIterator(<<<EOF
"col1", "col2"
"a21"
EOF
		, array('ignoreMissingRows' => true));

		$data = iterator_to_array($it);
		$this->assertEquals(array('col1' => 'a21', 'col2' => null), $data[0]);
	}

	/** @test */
	public function testStringCsvWithToMuchRowsIgnored()
	{
		$it = new StringCsvIterator(<<<EOF
"col1", "col2"
"a21", "a22", "a23"
EOF
		, array('ignoreMissingRows' => true));

		$data = iterator_to_array($it);
		$this->assertEquals(array('col1' => 'a21', 'col2' => 'a22'), $data[0]);
	}
}

