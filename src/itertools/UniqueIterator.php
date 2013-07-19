<?php

namespace itertools;

use FilterIterator;


class UniqueIterator extends FilterIterator
{
	const COMPARE_STRICT = 'COMPARE_STRICT';
	const COMPARE_NONSTRICT = 'COMPARE_NONSTRICT';

	protected $compareType;

	public function __construct($iterator, $compareType = self::COMPARE_STRICT)
	{
		parent::__construct(new HistoryIterator($iterator));
		$this->compareType = $compareType;
	}

	public function accept()
	{
		if(!$this->getInnerIterator()->hasPrev()) {
			return true;
		}
		if($this->compareType == self::COMPARE_STRICT) {
			return $this->current() !== $this->getInnerIterator()->prev();
		} else {
			return $this->current() != $this->getInnerIterator()->prev();
		}
	}
}
 
