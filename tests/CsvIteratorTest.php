<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class CsvIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testCsvIteratorWithHeader()
	{
		$fp = fopen('php://memory', 'rw');
		fwrite($fp, <<<EOF
"col1", "col2"
"a11", "a12"
"a21", "a22"
EOF
		);
		rewind($fp);

		$data = iterator_to_array(new CsvIterator($fp));
		$this->assertEquals(2, count($data));
		$this->assertEquals('a22', $data[1]['col2']);
	}

	/** @test */
	public function testCsvIteratorWithHeaderAndNoContent()
	{
		$fp = $this->getMemoryFileHandle(<<<EOF
"col1", "col2"
EOF
		);
		$data = iterator_to_array(new CsvIterator($fp));
		$this->assertEquals(0, count($data));
	}

	/** @test */
	public function testCsvIteratorWithHeaderAndNoData()
	{
		$fp = $this->getMemoryFileHandle(<<<EOF
EOF
		);

		$data = iterator_to_array(new CsvIterator($fp));
		$this->assertEquals(0, count($data));
	}

	/** @test */
	public function testCsvIteratorWithoutHeader()
	{
		$fp = $this->getMemoryFileHandle(<<<EOF
"col1", "col2"
"a11", "a12"
EOF
		);

		$data = iterator_to_array(new CsvIterator($fp, array('hasHeader' => false)));
		$this->assertEquals(2, count($data));
		$this->assertEquals('col1', $data[0][0]);
	}

	/** @test */
	public function testCsvIteratorWithEnclosureSameAsDelimiter()
	{
		$fp = $this->getMemoryFileHandle(<<<EOF
col1,col2
a11,a12
"a2""1",a22
EOF
		);

		$data = iterator_to_array(new CsvIterator($fp, array('escape' => '"', 'enclosure' => '"')));
		$this->assertEquals(2, count($data));
		$this->assertEquals('a11', $data[0]['col1']);
		$this->assertEquals('a2"1', $data[1]['col1']);
		$this->assertEquals('a22', $data[1]['col2']);
	}


	protected function getMemoryFileHandle($content)
	{
		$fp = fopen('php://memory', 'rw');
		fwrite($fp, $content);
		rewind($fp);
		return $fp;
	}
}

