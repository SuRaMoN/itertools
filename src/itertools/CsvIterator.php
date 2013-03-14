<?php

namespace itertools;

use Exception;
use EmptyIterator;


class CsvIterator extends TakeWhileIterator
{
	protected $fileHandle;
	protected $closeFileHandleOnDestruct;
	protected $options;

	public function __construct($file, array $options = array())
	{
		$defaults = array(
			'length' => 0,
			'delimiter' => ',',
			'enclosure' => '"',
			'escape' => '\\',
			'hasHeader' => true,
		);
		$this->options = (object) array_merge($defaults, $options);

		if(is_resource($file)) {
			$this->fileHandle = $file;
			$this->closeFileHandleOnDestruct = false;
		} else {
			$this->fileHandle = fopen($file, 'r');
			$this->closeFileHandleOnDestruct = true;
		}

		if($this->fileHandle === false) {
			throw new Exception('Could not open csv file');
		}

		if($this->options->hasHeader) {
			$it = $this->getLineIteratorWithHeader();
		} else {
			$it = $this->getLineIteratorWithoutHeader();
		}
		parent::__construct($it, function($r) { return $r !== false; });
	}

	protected function getLineIteratorWithoutHeader() {
		$fileHandle = $this->fileHandle;
		$options = $this->options;
		return new CallbackIterator(function() use ($fileHandle, $options) {
			return fgetcsv($fileHandle, $options->length, $options->delimiter, $options->enclosure, $options->escape);
		});
	}

	protected function getLineIteratorWithHeader() {
		$header = fgetcsv($this->fileHandle, $this->options->length, $this->options->delimiter, $this->options->enclosure, $this->options->escape);
		$options = $this->options;
		$fileHandle = $this->fileHandle;
		if($header === false) {
			return new EmptyIterator();
		}
		return new CallbackIterator(function() use ($fileHandle, $header, $options) {
			$line = fgetcsv($fileHandle, $options->length, $options->delimiter, $options->enclosure, $options->escape);
			return $line === false ? false : array_combine($header, $line);
		});
	}

	public function __destruct()
	{
		if($this->fileHandle !== null && $this->closeFileHandleOnDestruct) {
			fclose($this->fileHandle);
			$this->fileHandle = null;
		}
	}
}

