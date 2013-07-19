<?php

namespace itertools;


use ArrayIterator;


class StringCsvIterator extends AbstractCsvIterator
{
	protected $lines;

	public function __construct($input, array $options = array())
	{
		if(is_string($input)) {
			$input = new ArrayIterator(preg_split('/\r\n|\n|\r/', $input));
		}
		$this->lines = IterUtil::asIterator($input);
		$this->lines->rewind();
		parent::__construct($options);
	}

	public function retrieveNextCsvRow()
	{
		$nextLine = IterUtil::getCurrentAndAdvance($this->lines, array('default' => false));
		if($nextLine === false) {
			return false;
		}
		$rows = str_getcsv($nextLine, $this->options['delimiter'], $this->options['enclosure'], $this->options['escape']);
		return count($rows) == 0 ? false : $rows;
	}
}
