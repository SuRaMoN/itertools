<?php

namespace itertools;

use InvalidArgumentException;
use EmptyIterator;


class FileLineIterator extends TakeWhileIterator
{
	protected $fileHandle;
	protected $closeFileHandleOnDestruct;

	public function __construct($file, $options = array())
	{
		$defaultOptions = array(
			'includeWhitespace' => false,
		);

		$unknownOptions = array_diff(array_keys($options), array_keys($defaultOptions));
		if(count($unknownOptions) != 0) {
			throw new InvalidArgumentException('Unknown options specified: ' . implode(', ', $unknownOptions));
		}

		$options = (object) array_merge($defaultOptions, $options);

		if(is_resource($file)) {
			$this->fileHandle = $file;
			$this->closeFileHandleOnDestruct = false;
		} else if(is_string($file)) {
			$this->fileHandle = fopen($file, 'r');
			if($this->fileHandle === false) {
				throw new InvalidArgumentException("Could not open file with path: '$file'");
			}
			$this->closeFileHandleOnDestruct = true;

		} else {
			throw new InvalidArgumentException('You must provide either a stream or filename to the file line iterator, you provided a ' . gettype($file));
		}

		$fileHandle = $this->fileHandle;
		$lineIterator = new CallbackIterator(function() use ($fileHandle, $options) {
			$line = fgets($fileHandle);
			if($line !== false && !$options->includeWhitespace) {
				$line = rtrim($line, "\r\n");
			}
			return $line;
		});
		parent::__construct($lineIterator, function($line) { return $line !== false; });
	}

	public function __destruct()
	{
		if($this->fileHandle !== null && $this->closeFileHandleOnDestruct) {
			fclose($this->fileHandle);
			$this->fileHandle = null;
		}
	}
}

