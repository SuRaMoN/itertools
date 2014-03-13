<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class TemplateToSubstringMapConverterTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testSemiColonSeprated()
	{
		$converter = new TemplateToSubstringMapConverter();
		$map = $converter->convert('a ; b   ; c  ');
		$this->assertEquals(0, $map['a']->getOffset());
		$this->assertEquals(2, $map['a']->getLength());
		$this->assertEquals(4, $map['b']->getOffset());
		$this->assertEquals(4, $map['b']->getLength());
		$this->assertEquals(10, $map['c']->getOffset());
		$this->assertEquals(3, $map['c']->getLength());
	}

	/** @test */
	public function testSpecifiedLengths()
	{
		$converter = new TemplateToSubstringMapConverter();
		$map = $converter->convert('<a  >   <b      >     <c   >  ');
		$this->assertEquals(0, $map['a']->getOffset());
		$this->assertEquals(5, $map['a']->getLength());
		$this->assertEquals(8, $map['b']->getOffset());
		$this->assertEquals(9, $map['b']->getLength());
		$this->assertEquals(22, $map['c']->getOffset());
		$this->assertEquals(6, $map['c']->getLength());
	}

	/** @test */
	public function testAll()
	{
		$converter = new TemplateToSubstringMapConverter();
		$map = $converter->convert('a <b  > c1; d', array('c1' => 'new-name'));
		$this->assertEquals(0, $map['a']->getOffset());
		$this->assertEquals(2, $map['a']->getLength());
		$this->assertEquals(2, $map['b']->getOffset());
		$this->assertEquals(5, $map['b']->getLength());
		$this->assertEquals(8, $map['new-name']->getOffset());
		$this->assertEquals(2, $map['new-name']->getLength());
	}
}

