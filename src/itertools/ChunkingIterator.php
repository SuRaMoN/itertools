<?php

namespace itertools;

use IteratorIterator;
use Traversable;
use ArrayIterator;


/**
 * splits a iterator into smaller chunks. this can be used for batch processing.
 *
 * Example:
 *     $iterator = new rangeiterator();
 *     $batchsize = 100;
 *     foreach(new hunkingiterator($iterator, $batchsize) as $chunk) {
 *         $pdo->starttransaction();
 *         foreach($chunk as $element) {
 *             // process the iterator elements. using the transaction inside the chunkiterator makes sure the transaction stays small
 *         }
 *         $pdo->commit();
 *     }
 */
class ChunkingIterator extends IteratorIterator
{
    protected $chunkSize;
    protected $chunk;

    public function __construct($iterator, $chunkSize) {
        parent::__construct(is_array($iterator) ? new ArrayIterator($iterator) : $iterator);
        $this->chunkSize = $chunkSize;
		$this->chunk = array();
    }

    public function rewind() {
		$this->getInnerIterator()->rewind();
		$this->chunk = array();
    }

	protected function ensureBatchPresent() {
		if(!is_null(key($this->chunk))) {
			// chunk not completely fetched;
			return;
		}
        $inner = $this->getInnerIterator();
        for($i = 0; $i < $this->chunkSize && $inner->valid(); $i++) {
            $this->chunk[] = $inner->current();
            $inner->next();
        }
	}

    public function next() {
        $this->chunk = array();
    }

    public function current() {
		$this->ensureBatchPresent();
        return $this->chunk;
    }

    public function valid() {
		$this->ensureBatchPresent();
        return !empty($this->chunk);
    }
}

