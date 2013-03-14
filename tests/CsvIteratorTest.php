<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class CsvIteratorTest extends PHPUnit_Framework_TestCase {

	/** @test */
	public function testCsvIteratorWithHeader() {
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
	public function testCsvIteratorWithHeaderAndNoContent() {
		$fp = fopen('php://memory', 'rw');
		fwrite($fp, <<<EOF
"col1", "col2"
EOF
		);
		rewind($fp);

		$data = iterator_to_array(new CsvIterator($fp));
		$this->assertEquals(0, count($data));
	}

	/** @test */
	public function testCsvIteratorWithHeaderAndNoData() {
		$fp = fopen('php://memory', 'rw');
		fwrite($fp, <<<EOF
EOF
		);
		rewind($fp);

		$data = iterator_to_array(new CsvIterator($fp));
		$this->assertEquals(0, count($data));
	}

	/** @test */
	public function testCsvIteratorWithoutHeader() {
		$fp = fopen('php://memory', 'rw');
		fwrite($fp, <<<EOF
"col1", "col2"
"a11", "a12"
EOF
		);
		rewind($fp);

		$data = iterator_to_array(new CsvIterator($fp, array('hasHeader' => false)));
		$this->assertEquals(2, count($data));
		$this->assertEquals('col1', $data[0][0]);
	}
}

