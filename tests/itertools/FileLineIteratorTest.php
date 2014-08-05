<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class FileLineIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testReadingFileLines()
	{
		$fp = $this->getMemoryFileHandle(<<<EOF
line1
line2
line3
EOF
		);

		$lines = iterator_to_array(new FileLineIterator($fp));
		$this->assertEquals(array('line1', 'line2', 'line3'), $lines);
	}

	/** @test */
	public function testClosingHandle()
	{
		$lines = new FileLineIterator(__FILE__);
		$lines->__destruct();
	}

	/** @test */
	public function testReadingFileLinesFromFile()
	{
		$lines = iterator_to_array(new FileLineIterator(__FILE__));
		$this->assertGreaterThan(10, $lines);
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function testFileLineIteratorWithInvalidOptions()
	{
		new FileLineIterator(__FILE__, array('unknownOption' => true));
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function testFileLineIteratorWithNonExistingFilePath()
	{
		new FileLineIterator(uniqid());
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function testFileLineIteratorWithInvalidArguments()
	{
		new FileLineIterator(1);
	}

	/** @test */
	public function testFileCsvWithCustomCharacterEncoding()
	{
		$data = iterator_to_array(new FileLineIterator(__DIR__ . '/testdata/testline-latin1.txt', array('fromEncoding' => 'ISO-8859-1')));
		$this->assertEquals("Josephin\xc3\xa8", $data[0]);
		$this->assertEquals("Albr\xc3\xa9ne", $data[1]);
	}

	protected function getMemoryFileHandle($content)
	{
		$fp = fopen('php://memory', 'rw');
		fwrite($fp, $content);
		rewind($fp);
		return $fp;
	}
}

