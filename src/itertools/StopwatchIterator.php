<?php

namespace itertools;

use DateInterval;
use DateTime;
use InvalidArgumentException;
use IteratorIterator;


class StopwatchIterator extends IteratorIterator
{
	protected $startTime;
	protected $stopTime;
	protected $iterationCount;
	protected $options;
	protected $printToFileHandle;
	protected $closePrintToFileHandleOnDestruct;
	protected $previousPrintTime;

	public function __construct($iterable, $options = array())
	{
		parent::__construct(IterUtil::asTraversable($iterable));

		$this->iterationCount = 0;

		$defaultOptions = array(
			'autoPrint' => false,
			'printTo' => 'php://stdout',
			'printInterval' => DateInterval::createFromDateString('5 seconds'),
		);
		$this->options = array_merge($defaultOptions, $options);

		if(! $this->options['printInterval'] instanceof DateInterval) {
			throw new InvalidArgumentException('printInterval must be an instance of DateInterval');
		}

		$unknownOptions = array_diff(array_keys($options), array_keys($defaultOptions));
		if(count($unknownOptions) != 0) {
			throw new InvalidArgumentException('Unknown options specified: ' . implode(', ', $unknownOptions));
		}

		if($this->options['autoPrint']) {
			$file = $this->options['printTo'];
			if(is_resource($file)) {
				$this->printToFileHandle = $file;
				$this->closePrintToFileHandleOnDestruct = false;
			} else if(is_string($file)) {
				$this->printToFileHandle = @fopen($file, 'a');
				if($this->printToFileHandle === false) {
					throw new InvalidArgumentException("Could not open file with path: '$file'");
				}
				$this->closePrintToFileHandleOnDestruct = true;
			} else {
				throw new InvalidArgumentException('You must provide either a stream or filename as printTo argument, you provided a ' . gettype($file));
			}
		}
	}

	public function __destruct()
	{
		if(null !== $this->printToFileHandle && $this->closePrintToFileHandleOnDestruct) {
			fclose($this->printToFileHandle);
			$this->printToFileHandle = null;
		}
	}

	protected function autoPrintSpeed()
	{
		if(date_create('now')->sub($this->options['printInterval']) < $this->previousPrintTime) {
			return;
		}
		fputs($this->printToFileHandle, "Speed (iterations/microsecond): {$this->getFormattedSpeed()}, Elapsed time (microsecond): {$this->getFormattedElapsedTime()}\n");
		$this->previousPrintTime = new DateTime('now');
	}
	

	public function rewind()
	{
		$this->startTime = microtime(true);
		$this->stopTime = null;
		$this->previousPrintTime = new DateTime('now');
		$this->iterationCount = 0;
		parent::rewind();
	}

	public function next()
	{
		if($this->options['autoPrint']) {
			$this->autoPrintSpeed();
		}
		$this->iterationCount += 1;
		parent::next();
	}

	public function valid()
	{
		$isValid = parent::valid();
		if(! $isValid) {
			$this->stopTime = microtime(true);
		}
		return $isValid;
	}

	public function getFormattedSpeed()
	{
		return number_format($this->getSpeed(), 4);
	}

	public function getSpeed()
	{
		$elapsedTime = $this->getElapsedTime();
		if(0. == $elapsedTime) {
			return NAN;
		}
		return $this->iterationCount / $elapsedTime;
	}

	public function getFormattedElapsedTime()
	{
		return number_format($this->getElapsedTime(), 4);
	}

	public function getElapsedTime()
	{
		if(null !== $this->stopTime) {
			return $this->stopTime - $this->startTime;
		}
		if(null === $this->startTime) {
			return 0.;
		}
		return microtime(true) - $this->startTime;
	}
 
 	public function getIterationCount()
 	{
 		return $this->iterationCount;
 	}
 
 	public function getStartTime()
 	{
 		return $this->startTime;
 	}
 
 	public function getStopTime()
 	{
 		return $this->stopTime;
 	}
}

