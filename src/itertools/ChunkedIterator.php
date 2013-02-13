<?php

namespace itertools;

use IteratorIterator;
use Traversable;
use ArrayIterator;


class ChunkedIterator extends IteratorIterator
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

	private function ensureBatchPresent() {
		if(!is_null(key($this->chunk))) {
			// chunk not completely fetched;
			return;
		}
        $inner = $this->getInnerIterator();
        for ($i = 0; $i < $this->chunkSize && $inner->valid(); $i++) {
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

