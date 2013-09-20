<?php

namespace itertools;

use IteratorIterator;
use ArrayIterator;


class LockingIterator extends IteratorIterator
{
	protected $lockFp;
	protected $dir;
	protected $lockNameMapper;

	public function __construct($iterable, $dir, $lockNameMapper = null)
	{
		parent::__construct(IterUtil::asTraversable($iterable));
		$this->dir = $dir;
		$this->lockNameMapper = $lockNameMapper;
	}

	protected function lock($name)
	{
		if(!is_dir($this->dir)) {
			mkdir($this->dir, 0777, true);
		}
		$this->lockFp = fopen("{$this->dir}/$name", 'w+');
		flock($this->lockFp, LOCK_EX);
	}

	protected function unlock()
	{
		flock($this->lockFp, LOCK_UN);
		fclose($this->lockFp);
		$this->lockFp = null;
	}

	public function current()
	{
		$current = parent::current();
		if($this->lockFp === null) {
			$this->lock($this->getLockName($current));
		}
		return $current;
	}

	protected function getLockName($current)
	{
	    if(null === $this->lockNameMapper) {
			return $current;
		}
		return call_user_func($this->lockNameMapper, $current);
	}
	

	public function next()
	{
		if($this->lockFp !== null) {
			$this->unlock();
		}
		parent::next();
	}
}

 
