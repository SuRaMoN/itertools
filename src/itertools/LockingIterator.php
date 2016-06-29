<?php

namespace itertools;

use ArrayIterator;
use Exception;
use IteratorIterator;

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
        @mkdir($this->dir, 0777, true);
        if (! is_dir($this->dir) || ! is_writable($this->dir)) {
            throw new Exception("Could not create directory '{$this->dir}' to store lock files");
        }
        $this->lockFp = fopen("{$this->dir}/$name", 'w+');
        if (false === $this->lockFp) {
            throw new Exception("Error while trying to open lockfile '{$this->dir}/$name'");
        }
        $flockStatus = flock($this->lockFp, LOCK_EX);
        if (false === $flockStatus) {
            throw new Exception("Error while trying to lock file '{$this->dir}/$name'");
        }
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
        if ($this->lockFp === null) {
            $this->lock($this->getLockName($current));
        }
        return $current;
    }

    protected function getLockName($current)
    {
        if (null === $this->lockNameMapper) {
            return $current;
        }
        return call_user_func($this->lockNameMapper, $current);
    }
    

    public function next()
    {
        if ($this->lockFp !== null) {
            $this->unlock();
        }
        parent::next();
    }
}

 
