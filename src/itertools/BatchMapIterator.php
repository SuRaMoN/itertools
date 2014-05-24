<?php

namespace itertools;

use InvalidArgumentException;
use IteratorIterator;

/**
 * Maps iterator values using a callback function but in batches.
 */
class BatchMapIterator extends IteratorIterator
{
	const DONT_SPLIT_KEY_VALUES = 0;
	const SPLIT_KEY_VALUES = 1;

	const DONT_ADD_KEY_LIST = 0;
	const ADD_KEY_LIST = 2;

	const ALLOW_MERGE = 0;
	const DONT_ALLOW_MERGE = 4;

	protected $mapCallbacks;
	protected $type;
	protected $batchSize;
    protected $batch;
    protected $batchKeys;

	public function __construct($innerItererator, $mapCallback, $batchSize = 100, $type = 0)
	{
		if (! is_callable($mapCallback)) {
			throw new InvalidArgumentException('The callback must be callable');
		}
		if(($type & self::DONT_ALLOW_MERGE) == 0 && $innerItererator instanceof self && $batchSize == $innerItererator->getBatchSize() && $type == $innerItererator->getType()) {
			parent::__construct(IterUtil::asTraversable($innerItererator->getInnerIterator()));
			$this->mapCallbacks = array_merge($innerItererator->getMapCallbacks(), array($mapCallback));
		} else {
			parent::__construct(IterUtil::asTraversable($innerItererator));
			$this->mapCallbacks = array($mapCallback);
		}
		$this->type = $type;
		$this->batchSize = $batchSize;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getMapCallbacks()
	{
		return $this->mapCallbacks;
	}

	public function getBatchSize()
	{
		return $this->batchSize;
	}

	protected function ensureBatchPresent()
	{
		$batch = array();
		$batchKeys = array();
        $inner = $this->getInnerIterator();
		if(self::SPLIT_KEY_VALUES & $this->type) {
			for($i = 0; $i < $this->batchSize && $inner->valid(); $i++, $inner->next()) {
				$batch[] = array($inner->key(), $inner->current());
			}
		} else {
			for($i = 0; $i < $this->batchSize && $inner->valid(); $i++, $inner->next()) {
				$batch[] = $inner->current();
				$batchKeys[] = $inner->key();
			}
		}

		if((self::ADD_KEY_LIST & $this->type) != 0) {
			foreach($this->mapCallbacks as $mapCallback) {
				list($batchKeys, $batch) = call_user_func($mapCallback, $batchKeys, $batch);
			}
		} else {
			foreach($this->mapCallbacks as $mapCallback) {
				$batch = call_user_func($mapCallback, $batch);
			}
		}

		$this->batch = $batch;
		reset($this->batch);

		$this->batchKeys = $batchKeys;
		reset($this->batchKeys);
	}

    public function rewind()
	{
		$this->getInnerIterator()->rewind();
		$this->batch = array();
		$this->batchKeys = array();
    }

    public function valid()
	{
		if(null === key($this->batch)) {
			$this->ensureBatchPresent();
		}
        return null !== key($this->batch);
    }

    public function next()
	{
		next($this->batch);
		next($this->batchKeys);
    }

    public function key()
	{
		if(self::SPLIT_KEY_VALUES & $this->type) {
			$result = current($this->batch);
			return $result[0];
		} else {
			return current($this->batchKeys);
		}
    }

    public function current()
	{
		if(self::SPLIT_KEY_VALUES & $this->type) {
			$result = current($this->batch);
			return $result[1];
		} else {
			return current($this->batch);
		}
    }
}
 
