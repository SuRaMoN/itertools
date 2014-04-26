<?php

namespace itertools;

use BadMethodCallException;
use EmptyIterator;
use IteratorIterator;
use ReflectionClass;


class ComposingIterator extends ReferencingIterator
{
	protected static $filters = array(
		'caching' => true,
		'callbackFilter' => true,
		'chain' => true,
		'chunking' => true,
		'currentCached' => true,
		'dropWhile' => true,
		'groupBy' => true,
		'history' => true,
		'lookAhead' => true,
		'map' => true,
		'slice' => true,
		'stopwatch' => true,
		'stringCsv' => true,
		'takeWhile' => true,
		'unique' => true,
	);

	protected static $sources = array(
		'fileCsv' => true,
		'fileLine' => true,
		'pdo' => true,
		'processCsv' => true,
		'range' => true,
		'referencing' => true,
		'repeat' => true,
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
		switch(true) {
			case array_key_exists($name, self::$filters):
				return $this->pushIteratorByClassName($name, array_merge(array($this->getInnerIterator()), $arguments));
			case array_key_exists($name, self::$sources):
				return $this->pushIteratorByClassName($name, $arguments);
			default:
				throw new BadMethodCallException('Call to unknown method: ' . __NAMESPACE__ . '\\' . __CLASS__ . '::' . $name);
		}
	}

	protected function pushIteratorByClassName($name, $arguments)
	{
		$iteratorClassName = __NAMESPACE__ . '\\' . ucfirst($name) . 'Iterator';
		$reflector = new ReflectionClass($iteratorClassName);
		return $this->setInnerIterator($reflector->newInstanceArgs($arguments));
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

	public function fixedLengthFormattedStringFromTemplate($template, array $nameMap = array(), array $options = array())
	{
		return $this->setInnerIterator(FixedLengthFormattedStringIterator::newFromTemplate($this->getInnerIterator(), $template, $nameMap, $options));
	}

	public function skipFirst()
	{
		return $this->setInnerIterator(new SliceIterator($this->getInnerIterator(), 1));
	}

	public function cacheCurrent()
	{
		return $this->currentCached();
	}

	public function source($iterable)
	{
		return $this->setInnerIterator($iterable);
	}
}
 
