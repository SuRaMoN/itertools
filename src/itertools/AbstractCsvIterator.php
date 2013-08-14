<?php

namespace itertools;

use InvalidArgumentException;
use EmptyIterator;


abstract class AbstractCsvIterator extends TakeWhileIterator
{
	protected $options = array();

	public function __construct(array $options = array())
	{
		$defaultOptions = array(
			'delimiter' => ',',
			'enclosure' => '"',
			'escape'    => '\\',
			'hasHeader' => true,
			'header'    => null,
		);

		$unknownOptions = array_diff(array_keys($options), array_keys($defaultOptions));
		if(count($unknownOptions) != 0) {
			throw new InvalidArgumentException('Unknown options specified: ' . implode(', ', $unknownOptions));
		}

		$this->options = array_merge($this->options, $defaultOptions, $options);

		if($this->options['hasHeader']) {
			if(null === $this->options['header']) {
				$it = $this->getLineIteratorWithHeaderInFirstLine();
			} else {
				$it = $this->getLineIteratorWithHeader($this->options['header']);
			}
		} else {
			$it = $this->getLineIteratorWithoutHeader();
		}
		parent::__construct($it, function($r) { return $r !== false; });
	}

	protected function getLineIteratorWithoutHeader()
	{
		return new CallbackIterator(array($this, 'retrieveNextCsvRow'));
	}

	protected function getLineIteratorWithHeader($header)
	{
		if(false === $header || null === $header) {
			return new EmptyIterator();
		}
		$nextRowRetriever = array($this, 'retrieveNextCsvRow');
		return new CallbackIterator(function() use ($nextRowRetriever, $header) {
			$row = call_user_func($nextRowRetriever);
			return $row === false ? false : array_combine($header, $row);
		});
	}

	protected function getLineIteratorWithHeaderInFirstLine()
	{
		$header = $this->retrieveNextCsvRow();
		return $this->getLineIteratorWithHeader($header);
	}

	abstract public function retrieveNextCsvRow();
}

