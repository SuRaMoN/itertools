<?php

namespace itertools;

use RecursiveIterator;
use IteratorIterator;
use EmptyIterator;


class CallbackRecursiveIterator extends CachingIterator implements RecursiveIterator
{
	protected $getChildrenCallback;
	protected $currentChildrenCache;
	protected $isCurrentChildrenCacheValid = false;

	public function __construct($startNodes, callable $getChildrenCallback)
	{
		parent::__construct(IterUtil::asIterator($startNodes));
		$this->getChildrenCallback = $getChildrenCallback;
	}

	public function getChildren()
	{
		if($this->isCurrentChildrenCacheValid) {
			return $this->currentChildrenCache;
		}
		$children = call_user_func($this->getChildrenCallback, $this->current());
		if($children === null || $children === false) {
			$children = new EmptyIterator();
		}
		$this->currentChildrenCache = new self($children, $this->getChildrenCallback);
		$this->isCurrentChildrenCacheValid = true;
		return $this->currentChildrenCache;
	}

	public function hasChildren()
	{
		return $this->getChildren()->hasNext();
	}

	public function next()
	{
		$this->isCurrentChildrenCacheValid = false;
		return parent::next();
	}

	public function rewind()
	{
		$this->isCurrentChildrenCacheValid = false;
		return parent::rewind();
	}
}

