<?php

namespace itertools;

use IteratorIterator;


class CurrentCachedIterator extends IteratorIterator
{
	protected $cachedCurrent;
	protected $currentUpToDate;
	protected $cachedValid;
	protected $validUpToDate;

	public function __construct($innerIterator)
	{
		parent::__construct(IterUtil::asIterator($innerIterator));
	}

	public function invalidateCache() {
		$this->currentUpToDate = false;
		$this->validUpToDate = false;
	}

    public function rewind() {
		$this->invalidateCache();
		return parent::rewind();
    }

	public function uncachedCurrent() {
		return parent::current();
	}

    public function current() {
		if(!$this->currentUpToDate) {
			$this->cachedCurrent = $this->uncachedCurrent();
			$this->currentUpToDate = true;
		}
        return $this->cachedCurrent;
    }

    public function next() {
		$this->invalidateCache();
		return parent::next();
    }

	public function uncachedValid() {
		return parent::valid();
	}

    public function valid() {
		if($this->validUpToDate) {
			return $this->cachedValid;
		}
		$this->validUpToDate = true;
        return $this->cachedValid = $this->uncachedValid();
    }
}

