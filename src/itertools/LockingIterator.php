<?php

namespace itertools;

use IteratorIterator;
use ArrayIterator;


class LockingIterator extends IteratorIterator {
	protected $lockFp;
	protected $dir;
	protected $locknameMapper;

	public function __construct($inner, $dir, $locknameMapper = null) {
		parent::__construct(is_array($inner) ? new ArrayIterator($inner) : $inner);
		$this->dir = $dir;
		$this->locknameMapper = $locknameMapper;
	}

	protected function lock($name) {
		if(!is_dir($this->dir)) {
			mkdir($this->dir, 0777, true);
		}
		$this->lockFp = fopen("{$this->dir}/$name", 'w+');
		flock($this->lockFp, LOCK_EX);
	}

	protected function unlock() {
		flock($this->lockFp, LOCK_UN);
		fclose($this->lockFp);
		$this->lockFp = null;
	}

	public function current() {
		$current = parent::current();
		if($this->lockFp === null) {
			$this->lock(is_null($this->locknameMapper) ? $current : call_user_func($this->locknameMapper, $current));
		}
		return $current;
	}

	public function next() {
		if($this->lockFp !== null) {
			$this->unlock();
		}
		parent::next();
	}
}

 
