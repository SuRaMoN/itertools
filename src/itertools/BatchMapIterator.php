<?php

namespace itertools;


class BatchMapIterator extends ChainIterator
{
	public function __construct($innerItererator, $callback, $batchSize = 100)
	{
		$batches = new ChunkingIterator($innerItererator, $batchSize);
		$mappedBatches = new MapIterator($batches, $callback);
		return parent::__construct($mappedBatches, self::DONT_USE_KEYS);
	}
}
 
