<?php

namespace itertools;

use FilterIterator;

/**
 * Filters all null values from an iterator
 */
class NullFilterIterator extends FilterIterator
{
    public function __construct($iterator)
    {
        parent::__construct(IterUtil::asIterator($iterator));
    }

    public function accept()
    {
        return $this->current() !== null;
    }
}
