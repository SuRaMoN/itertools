<?php

namespace itertools;

use DateInterval;
use DateTime;
use Iterator;


class DateRangeIterator implements Iterator
{
	const INCLUDE_BOTH = 0;
	const EXCLUDE_LEFT = 1;
	const EXCLUDE_RIGHT = 2;

	protected $startDate;
	protected $currentDate;
	protected $endDate;
	protected $index;
	protected $borderInclusion;
	protected $interval;

	public function __construct($startDate, $endDate, $interval, $borderInclusion = self::INCLUDE_BOTH)
	{
		$this->borderInclusion = $borderInclusion;
		$this->startDate = is_string($startDate) ? new DateTime($startDate) : $startDate;
		$clonedStart = clone $this->startDate;
		$this->endDate = is_string($endDate) ? $clonedStart->modify($endDate) : $endDate;
		$this->interval = is_string($interval) ? DateInterval::createFromDateString($interval) : $interval;
	}

	public function valid()
	{
		if(null === $this->currentDate) {
			return false;
		}
		if($this->currentDate < $this->endDate) {
			return true;
		}
		return $this->currentDate == $this->endDate && !($this->borderInclusion & self::EXCLUDE_RIGHT);
	}

	public function rewind()
	{
		$this->index = 0;
		$this->currentDate = clone $this->startDate;
		if($this->borderInclusion & self::EXCLUDE_LEFT) {
			$this->next();
		}
	}

	public function next()
	{
		$this->currentDate->add($this->interval);
		$this->index += 1;
	}

	public function key()
	{
		return $this->index;
	}

	public function current()
	{
		return clone $this->currentDate;
	}
}

