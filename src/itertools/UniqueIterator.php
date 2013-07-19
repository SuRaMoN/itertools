<?php

namespace itertools;

use FilterIterator;


class UniqueIterator extends FilterIterator
{
	const COMPARE_STRICT = 'COMPARE_STRICT';
	const COMPARE_NONSTRICT = 'COMPARE_NONSTRICT';

	protected $options;

	public function __construct($iterator, $options = array())
	{
		parent::__construct(new HistoryIterator($iterator));

		$defaultOptions = array(
			'compareType' => self::COMPARE_STRICT,
		);

		$unknownOptions = array_diff(array_keys($options), array_keys($defaultOptions));
		if(count($unknownOptions) != 0) {
			throw new InvalidArgumentException('Unknown options specified: ' . implode(', ', $unknownOptions));
		}

		$this->options = array_merge($defaultOptions, $options);
	}

	public function accept()
	{
		if(!$this->getInnerIterator()->hasPrev()) {
			return true;
		}
		if($this->options['compareType'] == self::COMPARE_STRICT) {
			return $this->current() !== $this->getInnerIterator()->prev();
		} else {
			return $this->current() != $this->getInnerIterator()->prev();
		}
	}
}
 
