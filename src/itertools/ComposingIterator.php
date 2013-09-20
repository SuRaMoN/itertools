<?php

namespace itertools;

use ReflectionClass;
use EmptyIterator;
use IteratorIterator;


class ComposingIterator extends ReferencingIterator
{
	protected static $filters = array(
		'caching' => true,
		'callbackFilter' => true,
		'chain' => true,
		'chunking' => true,
		'furrentCached' => true,
		'groupBy' => true,
		'history' => true,
		'lookAhead' => true,
		'map' => true,
		'slice' => true,
		'takeWhile' => true,
		'unique' => true,
	);

	protected static $sources = array(
		'fileCsv' => true,
		'fileLine' => true,
		'pdo' => true,
		'range' => true,
		'referencing' => true,
		'repeat' => true,
		'stringCsv' => true,
		'zip' => true,
	);

	const USE_KEYS = true;
	const DONT_USE_KEYS = false;

	protected $innerIterator;

	public function __construct()
	{
		parent::__construct(new EmptyIterator());
	}

	public static function newInstance()
	{
		return new self();
	}

	public function toArray($useKeys = self::USE_KEYS)
	{
		return iterator_to_array($this, $useKeys);
	}

	public function count()
	{
		return iterator_count($this);
	}

	public function filter($callback)
	{
		return $this->callbackFilter($callback);
	}

	public function __call($name, $arguments)
	{
		$iteratorClassName = __NAMESPACE__ . '\\' . ucfirst($name) . 'Iterator';
		$reflector = new ReflectionClass($iteratorClassName);
		switch(true) {
			case array_key_exists($name, self::$filters):
				return $this->setInnerIterator($reflector->newInstanceArgs(
					array_merge(array($this->getInnerIterator()), $arguments)
				));
			case array_key_exists($name, self::$sources):
				return $this->setInnerIterator($reflector->newInstanceArgs($arguments));
		}
	}

	public function chunk($chunkSize)
	{
		return $this->chunking($chunkSize);
	}

	public function zipWith($iterable)
	{
		return $this->setInnerIterator(new ZipIterator(array($this->getInnerIterator(), $iterable)));
	}

	public function zipWithAll(array $iterables)
	{
		return $this->setInnerIterator(new ZipIterator(array_merge(array($this->getInnerIterator()), $iterables)));
	}

	public function cacheCurrent()
	{
		return $this->currentCached();
	}

	public function source($iterable)
	{
		return $this->referencing();
	}
}
 
