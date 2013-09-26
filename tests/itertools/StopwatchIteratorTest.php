<?php

namespace itertools;

use DateInterval;
use PHPUnit_Framework_TestCase;


class StopwatchIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testBasicFunctionality()
	{
		$stopwatchIterator = new StopwatchIterator(new RangeIterator(0, 10));
		$this->assertEquals(0, $stopwatchIterator->getElapsedTime());
		$this->assertTrue(is_nan($stopwatchIterator->getSpeed()));

		$count = 0;
		foreach($stopwatchIterator as $element) {
			usleep(10 * 1000);
			$this->assertEquals($count, $element);
			$this->assertEquals($count, $stopwatchIterator->getIterationCount());
			$count += 1;
			if($element != 0) {
				$this->assertGreaterThan(10, $stopwatchIterator->getSpeed());
			}
		}
		$this->assertLessThan(microtime(true), $stopwatchIterator->getStartTime());

		$elapsedTime = $stopwatchIterator->getElapsedTime();
		$this->assertEquals($elapsedTime, $stopwatchIterator->getElapsedTime(), 'Time counter should stop after finish');
		$this->assertNotNull($stopwatchIterator->getStopTime());
	}

	/** @test */
	public function testAutoPrint()
	{
		$fp = fopen('php://memory', 'r+');
		$stopwatchIterator = new StopwatchIterator(new RangeIterator(0, 10), array('autoPrint' => true, 'printTo' => $fp, 'printInterval' => DateInterval::createFromDateString('0 seconds')));
		foreach($stopwatchIterator as $element) {
		}
		rewind($fp);
		$this->assertEquals(11, substr_count(stream_get_contents($fp), "\n"));
	}

	/** @test */
	public function testAutoPrintWithStringFile()
	{
		$stopwatchIterator = new StopwatchIterator(new RangeIterator(0, 10), array('autoPrint' => true, 'printTo' => 'php://memory', 'printInterval' => DateInterval::createFromDateString('0 seconds')));
		foreach($stopwatchIterator as $element) {
		}
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function testAutoPrintWithInvalidStringFile()
	{
		$stopwatchIterator = new StopwatchIterator(new RangeIterator(0, 10), array('autoPrint' => true, 'printTo' => '', 'printInterval' => DateInterval::createFromDateString('0 seconds')));
		foreach($stopwatchIterator as $element) {
		}
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function testAutoPrintWithInvalidFile()
	{
		$stopwatchIterator = new StopwatchIterator(new RangeIterator(0, 10), array('autoPrint' => true, 'printTo' => 1234, 'printInterval' => DateInterval::createFromDateString('0 seconds')));
		foreach($stopwatchIterator as $element) {
		}
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function testAutoPrintWithInvalidPrintInterval()
	{
		$stopwatchIterator = new StopwatchIterator(new RangeIterator(0, 10), array('printInterval' => 123));
		foreach($stopwatchIterator as $element) {
		}
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function testFileCsvIteratorWithInvalidArguments()
	{
		$stopwatchIterator = new StopwatchIterator(new RangeIterator(0, 10), array('a' => 'b'));
	}
}

