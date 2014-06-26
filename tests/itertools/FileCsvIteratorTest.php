<?php

namespace itertools;

use PHPUnit_Framework_TestCase;
use SplFileInfo;


class FileCsvIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testFileCsvIteratorWithHeader()
	{
		$fp = $this->getMemoryFileHandle(<<<EOF
"col1", "col2"
"a11", "a12"
"a21", "a22"
EOF
		);

		$data = iterator_to_array(new FileCsvIterator($fp));
		$this->assertEquals(2, count($data));
		$this->assertEquals('a22', $data[1]['col2']);
	}

	/** @test */
	public function testFileCsvIteratorWithHeaderAndNoContent()
	{
		$fp = $this->getMemoryFileHandle(<<<EOF
"col1", "col2"
EOF
		);
		$data = iterator_to_array(new FileCsvIterator($fp));
		$this->assertEquals(0, count($data));
	}

	/** @test */
	public function testFileCsvIteratorWithHeaderAndNoData()
	{
		$fp = $this->getMemoryFileHandle(<<<EOF
EOF
		);

		$data = iterator_to_array(new FileCsvIterator($fp));
		$this->assertEquals(0, count($data));
	}

	/** @test */
	public function testFileCsvIteratorFromSplFileInfo()
	{
		$data = iterator_to_array(new FileCsvIterator(new SplFileInfo(__DIR__ . '/testdata/testcsv.csv')));
		$this->assertCount(2, $data);
	}

	/** @test */
	public function testFileCsvIteratorWithoutHeader()
	{
		$fp = $this->getMemoryFileHandle(<<<EOF
"col1", "col2"
"a11", "a12"
EOF
		);

		$data = iterator_to_array(new FileCsvIterator($fp, array('hasHeader' => false)));
		$this->assertEquals(2, count($data));
		$this->assertEquals('col1', $data[0][0]);
	}

	/** @test */
	public function testFileCsvIteratorWithReplacedHeader()
	{
		$fp = $this->getMemoryFileHandle(<<<EOF
"col1", "col2"
"a11", "a12"
EOF
		);

		$data = iterator_to_array(new FileCsvIterator($fp, array('hasHeader' => true, 'header' => array(1, 'bla'), 'ignoreFirstRow' => true)));
		$this->assertEquals(1, count($data));
		$this->assertEquals('a11', $data[0][1]);
		$this->assertEquals('a12', $data[0]['bla']);
	}

	/** @test */
	public function testFileCsvIteratorWithEnclosureSameAsDelimiter()
	{
		$fp = $this->getMemoryFileHandle(<<<EOF
col1,col2
a11,a12
"a2""1",a22
EOF
		);

		$data = iterator_to_array(new FileCsvIterator($fp, array('escape' => '"', 'enclosure' => '"')));
		$this->assertEquals(2, count($data));
		$this->assertEquals('a11', $data[0]['col1']);
		$this->assertEquals('a2"1', $data[1]['col1']);
		$this->assertEquals('a22', $data[1]['col2']);
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function testFileCsvIteratorWithInvalidArguments()
	{
		new FileCsvIterator(1);
	}

	/** @test */
	public function testClosingHandle()
	{
		$lines = new FileCsvIterator(__FILE__);
		$lines->__destruct();
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function testFileCsvIteratorWithNonExistingFilePath()
	{
		new FileCsvIterator(uniqid());
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function testFileCsvIteratorWithInvalidOptions()
	{
		new FileCsvIterator(__FILE__, array('unknownOption' => true));
	}

	protected function getMemoryFileHandle($content)
	{
		$fp = fopen('php://memory', 'rw');
		fwrite($fp, $content);
		rewind($fp);
		return $fp;
	}
}

