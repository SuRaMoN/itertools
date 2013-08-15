<?php

namespace itertools;

use InvalidArgumentException;


class FileCsvIterator extends AbstractCsvIterator
{
	protected $fileHandle;
	protected $closeFileHandleOnDestruct;

	public function __construct($file, array $options = array())
	{
		$defaultOptions = array(
			'length' => 0,
		);
		$this->options = array_merge($defaultOptions, $options);

		if(is_resource($file)) {
			$this->fileHandle = $file;
			$this->closeFileHandleOnDestruct = false;
		} else if(is_string($file)) {
			$this->fileHandle = @fopen($file, 'r');
			if($this->fileHandle === false) {
				throw new InvalidArgumentException("Could not open csv file with path: '$file'");
			}
			$this->closeFileHandleOnDestruct = true;
		} else {
			throw new InvalidArgumentException('You must provide either a stream or filename to the csv iterator, you provided a ' . gettype($file));
		}

		parent::__construct(array_diff_key($options, $defaultOptions));
	}

	public function retrieveNextCsvRow()
	{
		return fgetcsv($this->fileHandle, $this->options['length'], $this->options['delimiter'], $this->options['enclosure'], $this->options['escape']);
	}

	public function __destruct()
	{
		if($this->fileHandle !== null && $this->closeFileHandleOnDestruct) {
			fclose($this->fileHandle);
			$this->fileHandle = null;
		}
	}
}

