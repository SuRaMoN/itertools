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

	protected function getMemoryFileHandle($content)
	{
		$fp = fopen('php://memory', 'rw');
		fwrite($fp, $content);
		rewind($fp);
		return $fp;
	}
}

