<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class PDFFileTest extends PHPUnit_Framework_TestCase {

	/** @test */
	function testGetDimension() {
		foreach(new ForkingIterator(range(0, 20), array('maxChildren' => 3)) as $i) {
			var_dump($i);
			sleep(1);
		}
	}
}

