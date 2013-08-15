<?php

namespace itertools;

use Iterator;

/**
 * Iterator equivalent of [array_unique](http://be1.php.net/manual/en/function.array-unique.php)
 * but only works for sorted input.
 * Example:
 *     Iterator equivalent of [range](http://be1.php.net/manual/en/function.range.php).
 *     $lines = new SliceIterator(new FileLineIterator('file.txt'), 0, 1000); // will iterate the first 1000 lines of the file
 */
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

