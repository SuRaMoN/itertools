<?php

namespace itertools;

use IteratorIterator;
use ArrayIterator;


class LockingIterator extends IteratorIterator {
	protected $lock;
	protected $dir;

	public function __construct($inner, $dir) {
		parent::__construct(is_array($inner) ? new ArrayIterator($inner) : $inner);
		$this->dir = $dir;
	}

	protected function lock($name) {
		if(!is_dir($this->dir)) {
			mkdir($this->dir, 0777, true);
		}
		$this->lock = fopen("{$this->dir}/$name", 'w+');
		flock($this->lock, LOCK_EX);
	}

	protected function unlock() {
		flock($this->lock, LOCK_UN);
		fclose($this->lock);
		$this->lock = null;
	}

	public function current() {
		$current = parent::current();
		if($this->lock === null) {
			$this->lock($current);
		}
		return $current;
	}

	public function next() {
		if($this->lock !== null) {
			$this->unlock();
		}
		parent::next();
	}
}

 
