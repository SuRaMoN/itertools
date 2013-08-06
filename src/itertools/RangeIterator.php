<?php

namespace itertools;

use Iterator;


class RangeIterator implements Iterator
{
	protected $start;
	protected $end;
	protected $step;
	protected $currentValue;
	protected $iterationCount;

	public function __construct($start, $end = null, $step = 1)
	{
		if(null === $end) {
			$end = $step > 0 ? INF : -INF;
		}
		$this->start = $start;
		$this->end = $end;
		$this->step = $step;
	}

    public function rewind()
	{
		$this->currentValue = $this->start;
		$this->iterationCount = 0;
    }

    public function key()
	{
		return $this->iterationCount++;
    }

    public function current()
	{
		return $this->currentValue;
    }

    public function next()
	{
		$this->currentValue += $this->step;
    }

    public function valid()
	{
		if($this->step > 0) {
			return $this->currentValue <= $this->end;
		} else if($this->step < 0) {
			return $this->currentValue >= $this->end;
		}
		return true;
    }
}

