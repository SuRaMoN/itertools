<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class CallbackRecursiveIteratorTest extends PHPUnit_Framework_TestCase {

	/** @test */
	public function testMainFunctionality() {
		$root = array(
			'a',
			'b',
			array(
				'c1',
				array('c2kind'),
				'c3'
			),
			'd'
		);
		$it = new CallbackRecursiveIterator($root, function($e) {
			return is_array($e) ? $e : false;
		});
		$expected = array(
			'|-a',
			'|-b',
			'|-Array',
			'| |-c1',
			'| |-Array',
			'| | \-c2kind',
			'| \-c3',
			'\-d',
		);
		$i = 0;
		foreach(new \RecursiveTreeIterator($it) as $node) {
			$this->assertEquals($expected[$i], $node);
			$i += 1;
		}
	}
}

