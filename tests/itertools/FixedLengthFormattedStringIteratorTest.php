<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class FixedLengthFormattedStringIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testSimpleFunctionality()
	{
		$input = array(
			'jos            , 1  , m',
			'piet           , 120, m',
			'tutu le wallon , 50 , f',
		);
		$template = 
			'<name         >  age, g';
		$names = array('g' => 'gender');
		$result = iterator_to_array(FixedLengthFormattedStringIterator::newFromTemplate($input, $template, $names, array('trim' => ' ')));
		$this->assertEquals('jos', $result[0]['name']);
		$this->assertEquals('120', $result[1]['age']);
		$this->assertEquals('m', $result[1]['gender']);
	}

	/** @test */
	public function testComposingIterator()
	{
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function testFileCsvIteratorWithInvalidArguments()
	{
		new FixedLengthFormattedStringIterator(array(), array(), array('invalid' => 'option'));
	}
}

